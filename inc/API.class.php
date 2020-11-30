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
            default:
                HTTP::_404();
                break;
        }
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