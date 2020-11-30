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
            'user-name',
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


        /**
         * Create user
         */
        $user = DB::getInstance()->insert([

            'userName'  => $_POST['user-name'],
            'password'  => password_hash( $_POST['password'], PASSWORD_DEFAULT ),
            'isOnline'  => true,
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
            'ip'        => $_SERVER['REMOTE_ADDR']

        ]);


        // prevent sending the password in the response
        unset( $user->password );

        $message = self::createMessage( 'Created successfully', $user );
        HTTP::_201( $message );

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
}