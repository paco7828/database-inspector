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
</head>

<body>
    <h1>Rename Table: <?php echo htmlspecialchars($tableName); ?></h1>
    <form action="" method="POST">
        <label for="newTableName">New Table Name:</label>
        <input type="text" id="newTableName" name="newTableName" required>
        <input type="submit" value="Rename Table">
    </form>
    <a href="../connect.php">Back</a>
</body>

</html>