<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database deletion</title>
</head>

<body>
    <?php
    require_once "../connection/connection.php";

    Connection::setCredentials();
    Connection::setDatabaseName($_POST["dbnameInput"]);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $servername = $_SESSION["db_credentials"]["servername"];
        $username = $_SESSION['db_credentials']["username"];
        $password = $_SESSION["db_credentials"]["password"];
        $dbname = $_SESSION['db_credentials']["dbname"];

        $conn = Connection::startConnection();
        $sql = "DROP DATABASE $dbname";

        if ($conn->query($sql)) {
            header("Location: ../userPage.php");
        } else {
            echo "Error while deleting database";
        }
    }
    ?>
    <a id="backAnchor" href="../userPage.php">Esc</a>
    <script src="../js/backAnchor.js"></script>
</body>

</html>