<?php

    require_once( './inc/API.class.php' );
    require_once( './inc/HTTP.class.php' );

    if( !API::getAuthUser() )
    {
        HTTP::_302( 'login.php' );
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Live Users Dashboard</title>
        <link rel="stylesheet" href="./css/style.css" />
    </head>

    <body>
        <div class="dashboard-container">

            <div>
                <h1>Welcome, Evyatar!</h1>

                <a href="#">Logout</a>
            </div>

            <div class="online-users">
                <table>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Login Time</th>
                            <th>IP</th>
                        </tr>

                        <tr>
                            <th class="last-updated" colspan="3">
                                Last Updated: <span>N/A</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>

        <script src="./js/functions.js"></script>
        <script src="./js/api.js"></script>
        <script src="./js/index.js"></script>
    </body>

</html>