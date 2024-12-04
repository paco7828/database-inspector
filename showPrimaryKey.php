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

    $tableName = $_POST['tableNameInput'] ?? null;
    if (!$tableName) {
        die("Table name not specified");
    }

    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if (!$result) {
        die("An error has occurred: $conn->error");
    } else {
        $columns = $result->fetch_fields();
        $primaryKey = null;

        foreach ($columns as $col) {
            if ($col->flags & 0x02) {
                $primaryKey = $col->name;
                break;
            }
        }

        if ($primaryKey) {
            echo "Primary Key: " . htmlspecialchars($primaryKey);
        } else {
            echo "No primary key found in the table.";
        }
    }
    ?>
    <a id="backAnchor" href="showTableDetails.php">Back</a>
    <script src="backAnchor.js"></script>

</body>

</html>