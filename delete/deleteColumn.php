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

    $conn = Connection::startConnection();

    $showBackLink = true;

    if (isset($_SESSION["current_table"]) && isset($_POST["delColumn"])) {
        $tableName = $_SESSION["current_table"];
        $columnName = $_POST["delColumn"];

        if (preg_match('/^[a-zA-Z0-9_]+$/', $tableName) && preg_match('/^[a-zA-Z0-9_]+$/', $columnName)) {
            $sql = "ALTER TABLE `$tableName` DROP COLUMN `$columnName`";

            if ($conn->query($sql)) {
                header("Location: ../showTableDetails.php");
                exit();
            } else {
                echo "An error has occurred: $conn->error";
            }
        } else {
            echo "Invalid table or column name.";
        }
    } else {
        echo "Missing table or column name.";
    }

    if ($showBackLink) {
        echo "<br><a href='../showTableDetails.php'>Back</a>";
    }
    ?>

    <script src="js/backAnchor.js"></script>
</body>

</html>