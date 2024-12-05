<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
</head>

<body>
    <div id="container">
        <div id="constantCredentials">
            <h1>Log in with your credentials</h1>
            <form action="userPage.php" method="POST">
                <input autocomplete="off" type="text" placeholder="Server name..." name="servernameInput"><br>
                <input autocomplete="off" type="text" placeholder="Username..." name="usernameInput"><br>
                <input autocomplete="off" type="text" placeholder="Password..." name="passwordInput"><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
    <script src="simpleKeyNav.js"></script>
</body>

</html>