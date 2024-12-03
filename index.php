<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database inspector</title>
</head>

<body>
    <div id="container">
        <div id="credentialsDiv">
            <form action="connect.php" method="POST">
                <h1>Enter database credentials</h1>
                <input type="text" placeholder="Server name..." name="servernameInput"><br>
                <input type="text" placeholder="Username..." name="usernameInput"><br>
                <input type="text" placeholder="Password..." name="passwordInput"><br>
                <input type="text" placeholder="Database name..." name="dbnameInput"><br>
                <input type="submit" value="Connect!">
            </form>
        </div>
    </div>
</body>

</html>