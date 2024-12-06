<?php
require_once "../connection/connection.php";

if (!isset($_GET['tableName'])) {
    die("Table name not provided.");
}

$tableName = $_GET['tableName'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
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

        $sql = "DROP TABLE `" . $conn->real_escape_string($tableName) . "`";
        if ($conn->query($sql) === TRUE) {
            header("Location ../connect.php");
        } else {
            echo "<p>Error deleting table: $conn->error</p>";
        }

        $conn->close();
    } else {
        echo "<p>Table deletion canceled.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Table</title>
</head>

<body>
    <h1>Delete Table: <?php echo htmlspecialchars($tableName); ?></h1>
    <p>Are you sure you want to delete this table? This action cannot be undone.</p>
    <form action="" method="POST">
        <button type="submit" name="confirm" value="yes">Yes, Delete</button>
        <button type="submit" name="confirm" value="no">Cancel</button>
    </form>
    <a href="../connect.php">Back</a>
</body>

</html>