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
                                Last Updated: <span>2020-12-01 9:00:05</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Evyatar</td>
                            <td>2020-12-01 8:45</td>
                            <td>127.0.0.1</td>
                        </tr>

                        <tr>
                            <td>Shir</td>
                            <td>2020-12-01 8:45</td>
                            <td>192.168.10.1</td>
                        </tr>

                        <tr>
                            <td>John</td>
                            <td>2020-12-01 8:45</td>
                            <td>10.0.0.3</td>
                        </tr>

                        <tr>
                            <td>Jane</td>
                            <td>2020-12-01 8:45</td>
                            <td>8.8.8.8</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

</html>