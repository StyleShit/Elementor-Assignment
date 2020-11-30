<?php

/**
 * handle HTTP code responses
 */
class HTTP
{
    private static function json()
    {
        header('Content-Type: application/json');
    }


    public static function _200( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 200 OK' );
        die( json_encode( $message ) );
    }


    public static function _201( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 201 Created' );
        die( json_encode( $message ) );
    }


    public static function _404( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 404 Not Found' );
        die( json_encode( $message ) );
    }


    public static function _400( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 400 Bad Request' );
        die( json_encode( $message ) );
    }

    
    public static function _401( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 401 Unauthorized' );
        die( json_encode( $message ) );
    }


    public static function _409( $message = [] )
    {
        self::json();
        header( 'HTTP/1.0 409 Conflict' );
        die( json_encode( $message ) );
    }
}