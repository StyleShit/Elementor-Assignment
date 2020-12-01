<?php

    require_once( './inc/API.class.php' );
    require_once( './inc/HTTP.class.php' );

    if( API::getAuthUser() )
    {
        HTTP::_302( 'index.php' );
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Live Users Dashboard | Login</title>
        <link rel="stylesheet" href="./css/style.css" />
    </head>

    <body>

        <div class="form-container">

            <div class="loader loader-full-size"></div>

            <h1 class="form-title">Login:</h1>
        
            <form class="login-form">
                <input required id="email" type="email" class="text-input" placeholder="Email" />
                <input required id="password" type="password" class="text-input" placeholder="Password" />
                <input type="submit" value="Login 🎉">
            </form>

            <p>
                Don't have an account? <a href="sign-up.php">Sign Up!</a>
            </p>

        </div>

        <script src="./js/functions.js"></script>
        <script src="./js/api.js"></script>
        <script src="./js/login.js"></script>
    </body>

</html>