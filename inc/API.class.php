<?php


require_once( __DIR__ . '/functions.php' );
require_once( __DIR__ . '/HTTP.class.php' );
require_once( __DIR__ . '/DB.class.php' );

/**
 * API calls handling
 */

class API
{
    // dispatch API action using given action
    public static function dispatchAction( $action )
    {
        switch( $action )
        {
            case 'register':
                self::handleRegistration();
                break;

            case 'login':
                self::handleLogin();
                break;

            case 'logout':
                self::handleLogout();
                break;

            case 'go-online':
                self::goOnline();
                break;

            case 'go-offline':
                self::goOffline();
                break;

            case 'get-online-users':
                self::getOnlineUsers();
                break;

            case 'get-user':
                self::getUserById();
                break;

            default:
                HTTP::_404();
                break;
        }
    }


    // handle new user registration
    private static function handleRegistration()
    {
        /**
         * Validations
         */
        $requiredFields = [
            'email',
            'password',
            'password-confirm'
        ];

        if( sizeof( checkRequired( $_POST, $requiredFields ) ) != 0 )
        {
            $error = self::createError( 'Please fill all required fields' );
            HTTP::_400( $error );
        }

        if( $_POST['password'] != $_POST['password-confirm'] )
        {
            $error = self::createError( 'Passwords do not match' );
            HTTP::_400( $error );
        }

        if( sizeof( DB::getInstance()->where( 'email', $_POST['email'] ) ) > 0 )
        {
            $error = self::createError( 'Email already exists' );
            HTTP::_409( $error );
        }

        // TODO: validate email

        /**
         * Create user
         */
        $user = DB::getInstance()->insert([

            'email'         => $_POST['email'],
            'password'      => password_hash( $_POST['password'], PASSWORD_DEFAULT ),
            'isOnline'      => false,
            'loginsCount'   => 0

        ]);

        self::setUserEnvData( $user );


        // prevent sending the password in the response
        unset( $user->password );

        $message = self::createMessage( 'Created successfully', $user );
        HTTP::_201( $message );

    }


    // handle user login
    private static function handleLogin()
    {
        /**
         * Validations
         */
        $requiredFields = [
            'email',
            'password'
        ];

        if( sizeof( checkRequired( $_POST, $requiredFields ) ) != 0 )
        {
            $error = self::createError( 'Please fill all required fields' );
            HTTP::_400( $error );
        }


        /**
         * Login user
         */

        // find user by credentials
        $results = DB::getInstance()->whereMultiAnd([

            'email'     => $_POST['email'],
            'password'  => fn( $hash ) => password_verify( $_POST['password'], $hash )
            
        ]);

        if( sizeof( $results ) != 1 )
        {
            $error = self::createError( 'Wrong credentials' );
            HTTP::_401( $error );
        }


        // set user as online & grab their data
        $user = $results[0];
        self::setUserOnline( $user, true );
        self::setUserLoginTime( $user );
        self::setUserEnvData( $user );
        self::increaseUserLoginCount( $user );


        unset( $user->password );
        setcookie( 'login', json_encode( $user ), time() + 3600, '/' );


        $message = self::createMessage( 'Logged in successfully' );
        HTTP::_200( $message );
    }


    // handle user logout
    private static function handleLogout()
    {
        self::goOffline( false );

        setcookie( 'login', '', time() - 3600, '/' );

        $message = self::createMessage( 'Logged out successfully' );
        HTTP::_200( $message );
    }


    // set current logged in user as online
    private static function goOnline( $isOnline = true, $showSuccessMessage = true)
    {
        $user = self::getAuthUser();

        if( !$user )
        {
            $error = self::createError( 'User is not logged in' );
            HTTP::_401( $error );
        }

        self::setUserOnline( $user, $isOnline );

        if( $showSuccessMessage )
        {
            $message = self::createMessage( 'Successfully set online state to: ' . ( $isOnline ? 'true' : 'false' ) );
            HTTP::_200( $message );
        }
    }


    // set current logged in user as offline
    private static function goOffline( $showSuccessMessage = true )
    {
        self::goOnline( false, $showSuccessMessage );
    }


    // get currently authenticated user
    private static function getAuthUser()
    {
        if( isset( $_COOKIE['login'] ) )
        {
            $user = json_decode( $_COOKIE['login'] );

            if( $user->id )
                return $user;
        }

        return null;
    }


    // get all online users
    private static function getOnlineUsers()
    {
        $results = DB::getInstance()->where( 'isOnline', true );

        $keys = [
            'email',
            'loggedAt',
            'ip'
        ];

        // remove unnecessary keys
        $results = array_map( fn( $result ) => filterObjectKeys( $result, $keys ), $results );

        $message = self::createMessage( 'Found '. sizeof( $results ) .' result(s)', $results );
        HTTP::_200( $message );
    }


    // get user by their id
    private static function getUserById()
    {
        if( !isset( $_GET['user-id'] ) || empty( $_GET['user-id'] ) )
        {
            $error = self::createError( 'User ID is required' );
            HTTP::_400( $error );
        }

        $userId = trim( $_GET['user-id'] );
        $results = DB::getInstance()->where( 'id', $userId );

        if( sizeof( $results ) == 0)
        {
            $error = self::createError( 'User not found' );
            HTTP::_404( $error );
        }

        $user = filterObjectKeys( $results[0], [ 'email', 'userAgent', 'createdAt', 'loginsCount' ] );

        $message = self::createMessage( 'User found', $user );
        HTTP::_200( $message );
    }

    
    // create an API response message to the user
    private static function createMessage( $message, $data = '' )
    {
        $data = is_array( $data ) ? $data : [ $data ];

        return [
            'message' => $message,
            'data' => $data,
            'time' => time()
        ];
    }


    // create an API response error to the user
    private static function createError( $error, $data = '' )
    {
        $data = is_array( $data ) ? $data : [ $data ];

        return [
            'error' => $error,
            'data' => $data,
            'time' => time()
        ];
    }


    // set in the database if the user is online
    private static function setUserOnline( $user, $isOnline )
    {
        $user->isOnline = $isOnline;
        DB::getInstance()->update( $user->id, $user );
    }


    // set user login time to current timestamp
    private static function setUserLoginTime( $user )
    {
        $user->loggedAt = time();
        DB::getInstance()->update( $user->id, $user );
    }


    // set the user environment data ( IP & UA )
    private static function setUserEnvData( $user )
    {
        $user->userAgent    = $_SERVER['HTTP_USER_AGENT'];
        $user->ip           = $_SERVER['REMOTE_ADDR'];

        DB::getInstance()->update( $user->id, $user );
    }


    // increase the user logins count by 1
    private static function increaseUserLoginCount( $user )
    {
        $user->loginsCount++;
        DB::getInstance()->update( $user->id, $user );
    }
}