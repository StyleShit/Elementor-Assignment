<?php


// check for required fields in array
function checkRequired( $array, $required )
{
    $errors = [];

    foreach( $required as $r )
    {
        if( !isset( $array[$r] ) || empty( $array[$r] ) )
        {
            $errors[] = $r;
        }
    }

    return $errors;
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


// Dump & Die
function dd( $value )
{
    var_dump( $value );
    die;
}