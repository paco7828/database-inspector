<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once 'connection.php';

    Connection::setCredentials();

    $servername = $_SESSION['db_credentials']['servername'];
    $username = $_SESSION['db_credentials']['username'];
    $password = $_SESSION['db_credentials']['password'];

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        echo "<div>Unable to connect to the server: $conn->connect_error. ";
        echo "<a href='index.php'>Back</a></div>";
        exit();
    }

    ?>

    <div id="container">
        <div id="connectCreds">
            <form action="connect.php" method="POST">
                <h1>Enter database credentials</h1>
                <input autocomplete="off" type="text" placeholder="Database name..." name="dbnameInput"><br>
                <input type="submit" value="Connect!">
            </form>
        </div>
        <div id="deleteDbCreds">
            <form action="deleteDb.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this database?');">
                <h2>Delete database</h2>
                <input type="text" placeholder="Database name..." name="dbnameInput"><br>
                <input type="submit" value="Delete!">
            </form>
        </div>
        <div id="createDbCreds">
            <form action="createDb.php" method="POST">
                <h2>Create database</h2>
                <input type="text" placeholder="Database name..." name="dbnameInput"><br>
                <select name="collationSelect" id="collationSelect">
                    <option value="" selected>Collation</option>
                    <option value="utf8_hungarian_ci">utf8_hungarian_ci</option>
                    <option value="armscii8_bin">armscii8_bin</option>
                    <option value="armscii8_general_ci">armscii8_general_ci</option>
                    <option value="armscii8_general_nopad_ci">armscii8_general_nopad_ci</option>
                    <option value="armscii8_nopad_bin">armscii8_nopad_bin</option>
                </select>
                <br>
                <input type="submit" value="Create!">
            </form>
        </div>
    </div>
    <script src="simpleKeyNav.js"></script>
</body>

</html>