<?php


// check for required fields in array
function checkRequired( $array, $required )
{
    $validator = [
        'isValid' => true,
        'errors' => []
    ];

    foreach( $required as $r )
    {
        if( !isset( $array[$r] ) || empty( $array[$r] ) )
        {
            $validator['errors'] = $r;
            $validator['isValid'] = false;
        }
    }

    return ( object ) $validator;
}


// filter object keys and return only specific keys
function filterObjectKeys( $object, $keys )
{
    $output = [];

    foreach( $keys as $k )
    {
        if( property_exists( $object, $k )  )
        {
            $output[$k] = $object->{ $k };
        }
    }

    return ( object ) $output;
}


// validate email address
function isValidEmail( $email )
{
    return filter_var( $email, FILTER_VALIDATE_EMAIL );
}


// Dump & Die
function dd( $value )
{
    var_dump( $value );
    die;
}