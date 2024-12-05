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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableName = $_SESSION["current_table"];
        $columnName = $_POST["nameInput"];
        $columnType = $_POST["typeSelect"];
        $columnLength = $_POST["lengthInput"];
        $defaultValue = $_POST["defaultSelect"];
        $collation = $_POST["collationSelect"];
        $isNull = isset($_POST["isNullCheckbox"]) ? "NULL" : "NOT NULL";
        $indexKey = $_POST["indexSelect"];
        $autoIncrement = isset($_POST["autoIncCheckbox"]) ? "AUTO_INCREMENT" : "";
        $comment = $_POST["commentInput"];

        if (!empty($columnName) || !empty($columnType) || !empty($columnLength)) {
            $sql = "ALTER TABLE $tableName
                ADD COLUMN $columnName $columnType($columnLength) ";

            if (!empty($collation)) {
                $charset = explode("_", $collation);
                $sql .= "CHARACTER SET $charset[0] COLLATE $collation ";
            }

            if (!empty($defaultValue)) {
                $sql .= "DEFAULT '$defaultValue' ";
            }

            $sql .= $isNull;

            if (!empty($autoIncrement)) {
                $sql .= "AUTO_INCREMENT ";
            }

            if (!empty($comment)) {
                $sql .= "COMMENT '$comment' ";
            }

            if (!empty($indexKey)) {
                $sql .= match ($indexKey) {
                    'PRIMARY' => ", ADD PRIMARY KEY ($columnName)",
                    'UNIQUE' => ", ADD UNIQUE KEY ($columnName)",
                    'INDEX' => ", ADD INDEX $columnName ($columnName)",
                    'FULLTEXT' => ", ADD FULLTEXT ($columnName)",
                    'SPATIAL' => ", ADD SPATIAL INDEX $columnName ($columnName)",
                    default => ''
                };
            }


            if ($conn->query($sql)) {
                header("Location: showTableDetails.php");
            } else {
                echo "Error: $conn->error";
                echo "<a id='backAnchor' href='showTableDetails.php'>Back</a>";
            }
        } else {
            die("Essential property left empty.");
        }
    }
    ?>


    <script src="backAnchor.js"></script>
</body>

</html>