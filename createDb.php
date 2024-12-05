<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $servername = $_POST['servernameInput'];
        $username = $_POST['usernameInput'];
        $password = $_POST['passwordInput'];
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
            echo "Database has been created successfully: $newDbname";
        } else {
            echo "Error creating database: $conn->error";
        }

        $conn->close();
    }
    ?>

    <script src="backAnchor.js"></script>
</body>

</html>