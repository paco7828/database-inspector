<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once "../connection/connection.php";
    
    Connection::setCredentials();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servername = $_SESSION["db_credentials"]["servername"];
        $username = $_SESSION['db_credentials']["username"];
        $password = $_SESSION["db_credentials"]["password"];
        $newDbname = $_POST['dbnameInput'];
        $collation = $_POST["collationSelect"];

        $conn = new mysqli($servername, $username, $password);

        if ($conn->connect_error) {
            die("Connection failed: $conn->connect_error");
        }

        $sql = "CREATE DATABASE IF NOT EXISTS `$newDbname`";

        if(!empty($collation)){
            $charset = explode("_", $collation)[0];
            $sql .= " CHARACTER SET $charset COLLATE $collation";
        }

        if ($conn->query($sql) === TRUE) {
            header("Location: ../userPage.php");
        } else {
            echo "<h1>Error creating database: $conn->error</h1>";
        }

        $conn->close();
    }
    ?>
    <a id="backAnchor" href="../userPage.php">Esc</a>
    <script src="../js/backAnchor.js"></script>
</body>

</html>