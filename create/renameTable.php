<?php
require_once "../connection/connection.php";

if (!isset($_GET['tableName'])) {
    die("Table name not provided.");
}

$tableName = $_GET['tableName'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newTableName']) && !empty($_POST['newTableName'])) {
        $newTableName = $_POST['newTableName'];

        Connection::setCredentials();
        Connection::setDatabaseName($_SESSION['dbname']);

        $servername = $_SESSION["db_credentials"]["servername"];
        $username = $_SESSION["db_credentials"]["username"];
        $password = $_SESSION["db_credentials"]["password"];
        $dbname = $_SESSION["db_credentials"]["dbname"];

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: $conn->connect_error");
        }

        $sql = "RENAME TABLE `" . $conn->real_escape_string($tableName) . "` TO `" . $conn->real_escape_string($newTableName) . "`";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../connect.php");
        } else {
            echo "<p>Error renaming table: $conn->error</p>";
        }

        $conn->close();
    } else {
        echo "<p>Please enter a valid new table name.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rename Table</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/backAnchor.css">
    <style>
        #container form{
            gap: 10px;
        }
    </style>
</head>

<body>
    <div id="container">
        <h1>Rename Table: <?php echo htmlspecialchars($tableName); ?></h1>
        <form action="" method="POST">
            <input type="text" id="newTableName" name="newTableName" placeholder="New table name..." required>
            <input type="submit" value="Rename!">
        </form>
    </div>
    <a id="backAnchor" href="../connect.php">Esc</a>
    <script src="../js/backAnchor.js"></script>
</body>

</html>