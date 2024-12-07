<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once 'connection/connection.php';

    Connection::setCredentials();

    if (isset($_SESSION['db_credentials'])) {
        $servername = $_SESSION['db_credentials']['servername'];
        $username = $_SESSION['db_credentials']['username'];
        $password = $_SESSION['db_credentials']['password'];
    } else {
        echo "<div>Database credentials are missing. ";
        echo "<a href='./index.php'>Back</a></div>";
        exit();
    }

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        echo "<div>Unable to connect to the server: $conn->connect_error. ";
        echo "<a href='./index.php'>Back</a></div>";
        exit();
    }
    ?>

    <div id="container">
        <div id="connectDbDiv">
            <form action="./connect.php" method="POST">
                <h2>Enter database credentials</h2>
                <select name="dbnameInput" id="dbnameInput">
                    <?php include "loadDbs.php"; ?>
                </select><br>
                <input type="submit" value="Connect!">
            </form>
        </div>
        <div id="deleteDbDiv">
            <form action="delete/deleteDb.php" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this database?');">
                <h2>Delete database</h2>
                <select name="dbnameInput" id="dbnameInput">
                    <?php include "loadDbs.php"; ?>
                </select><br>
                <input type="submit" value="Delete!">
            </form>
        </div>
        <div id="createDbDiv">
            <form action="create/createDb.php" method="POST">
                <h2>Create database</h2>
                <input type="text" autocomplete="off" placeholder="Database name..." name="dbnameInput"><br>
                <select name="collationSelect" id="collationSelect">
                    <option value="" selected>Select collation</option>
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
        <a id="backAnchor" href="./index.php">Back</a>
    </div>
    <script src="js/backAnchor.js"></script>
    <script src="js/simpleKeyNav.js"></script>
</body>

</html>