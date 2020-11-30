<?php

require_once( './inc/HTTP.class.php' );
require_once( './inc/API.class.php' );


if( isset( $_GET['action'] ) )
{
    $action = trim( $_GET['action'] );
    API::dispatchAction( $action );
}

else
{
    HTTP::_404();
}
