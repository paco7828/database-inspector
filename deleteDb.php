<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once "connection.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $servername = $_POST['servernameInput'];
        $username = $_POST['usernameInput'];
        $password = $_POST['passwordInput'];
        $dbname = $_POST['dbnameInput'];

        $sql = "DROP DATABASE $dbname";

        if ($conn->query($sql)) {
            echo "<h3>Database deleted successfully</h3>";
        } else {
            echo "Error while deleting database";
        }
        echo "<a id='backAnchor' href='index.php'></a>";
    }
    ?>
    <script src="backAnchor.js"></script>
</body>

</html>