<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available tables</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #container{
            text-align: center;
            margin-top: 15%;
        }

        #tablesDiv {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>

<body>
    <div id="container">
        <?php
        require_once "connection.php";

        echo "<h1>Choose a table</h1><div id='tablesDiv'>";

        $servername = $_SESSION["db_credentials"]["servername"];
        $username = $_SESSION["db_credentials"]["username"];
        $password = $_SESSION["db_credentials"]["password"];
        $dbname = $_SESSION["db_credentials"]["dbname"];
        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = '$dbname'";
        $result = $conn->query($sql);

        if (!$result) {
            die("An error has occurred: $conn->error");
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "
        <form action='showTableDetails.php' method='GET'>
            <input type='submit' value='" . $row["table_name"] . "' name='tableName'>
        </form>";
            }
        }
        echo "</div><a id='backAnchor' href='index.php'>Back</a>";
        ?>
        <script src="backAnchor.js"></script>
    </div>
</body>

</html>