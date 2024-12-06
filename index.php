<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/index.css">
</head>

<body>
    <div id="container" class="container-fluid text-center">
        <div id="constantCredentials">
            <h1>Credentials</h1>
            <form action="./userPage.php" method="POST">
                <input autocomplete="off" type="text" placeholder="Server name..." name="servernameInput" class="form-control-md"><br>
                <input autocomplete="off" type="text" placeholder="Username..." name="usernameInput" class="form-control-md"><br>
                <input autocomplete="off" type="text" placeholder="Password..." name="passwordInput" class="form-control-md"><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
    <script src="js/simpleKeyNav.js"></script>
</body>

</html>