<?php

session_start();

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

            'email'  => $_POST['email'],
            'password'  => password_hash( $_POST['password'], PASSWORD_DEFAULT ),
            'isOnline'  => false

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

            'email' => $_POST['email'],
            'password' => fn( $hash ) => password_verify( $_POST['password'], $hash )
            
        ]);

        if( sizeof( $results ) != 1 )
        {
            $error = self::createError( 'Wrong credentials' );
            HTTP::_401( $error );
        }


        // set user as online & grab their data
        $user = $results[0];
        self::setUserOnline( $user, true );
        self::setUserEnvData( $user );


        $_SESSION['login'] = $user;


        $message = self::createMessage( 'Logged in successfully' );
        HTTP::_200( $message );
    }


    // create an API response message to the user
    private static function createMessage( $message, $data = '' )
    {
        return [
            'message' => $message,
            'data' => [ $data ],
            'time' => time()
        ];
    }


    // create an API response error to the user
    private static function createError( $error, $data = '' )
    {
        return [
            'error' => $error,
            'data' => [ $data ],
            'time' => time()
        ];
    }


    // set in the database if the user is online
    private static function setUserOnline( $user, $isOnline )
    {
        $user->isOnline = $isOnline;
        DB::getInstance()->update( $user->id, $user );
    }


    // set the user environment data ( IP & UA )
    private static function setUserEnvData( $user )
    {
        $user->userAgent    = $_SERVER['HTTP_USER_AGENT'];
        $user->ip           = $_SERVER['REMOTE_ADDR'];

        DB::getInstance()->update( $user->id, $user );
    }
}