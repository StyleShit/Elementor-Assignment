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
        <title>Live Users Dashboard | Sign Up</title>
        <link rel="stylesheet" href="./css/style.css" />
    </head>

    <body>

        <div class="form-container">

            <div class="loader loader-full-size"></div>

            <h1 class="form-title">Sign Up:</h1>
        
            <form class="sign-up-form">
                <input required id="email" type="email" class="text-input" placeholder="Email" />
                <input required id="user-name" type="text" class="text-input" placeholder="User Name" />
                <input required id="password" type="password" class="text-input" placeholder="Password" />
                <input required id="password-confirm" type="password" class="text-input" placeholder="Password Confirm" />
                <input type="submit" value="Sign Up 🎉">

            </form>
            
            <p>
                Already have an account? <a href="login.php">Login!</a>
            </p>
            
        </div>

        <script src="./js/functions.js"></script>
        <script src="./js/api.js"></script>
        <script src="./js/toast.js"></script>
        <script src="./js/sign-up.js"></script>
    </body>

</html>