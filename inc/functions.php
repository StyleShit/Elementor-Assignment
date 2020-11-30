<?php


// check for required fields in array
function checkRequired( $array, $required )
{
    $errors = [];

    foreach( $required as $r )
    {
        if( !isset( $array[$r] ) )
        {
            $errors[] = $r;
        }
    }

    return $errors;
}


// Dump & Die
function dd( $value )
{
    var_dump( $value );
    die;
}