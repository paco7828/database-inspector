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
    Connection::setDatabaseName($_SESSION["dbname"]);

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

        if (!empty($columnName) && !empty($columnType)) {
            $sql = "ALTER TABLE `$tableName` ADD COLUMN `$columnName` $columnType";

            if (!empty($columnLength)) {
                $sql .= "($columnLength)";
            }

            $stringTypes = ['CHAR', 'VARCHAR', 'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT'];
            if (in_array(strtoupper($columnType), $stringTypes) && !empty($collation)) {
                $charset = explode("_", $collation);
                $sql .= " CHARACTER SET $charset[0] COLLATE $collation";
            }

            $sql .= " $isNull";

            if (!empty($defaultValue)) {
                $sql .= " DEFAULT ";
                $sql .= ($defaultValue === "NULL" || $defaultValue === "CURRENT_TIMESTAMP")
                    ? $defaultValue
                    : "'$defaultValue'";
            }

            if (!empty($autoIncrement)) {
                $sql .= " $autoIncrement";
            }

            if (!empty($comment)) {
                $sql .= " COMMENT '$comment'";
            }

            if (!empty($indexKey)) {
                switch ($indexKey) {
                    case 'PRIMARY':
                        $sql .= ", ADD PRIMARY KEY (`$columnName`)";
                        break;
                    case 'UNIQUE':
                        $sql .= ", ADD UNIQUE KEY (`$columnName`)";
                        break;
                    case 'INDEX':
                        $sql .= ", ADD INDEX `$columnName` (`$columnName`)";
                        break;
                    case 'FULLTEXT':
                        $sql .= ", ADD FULLTEXT (`$columnName`)";
                        break;
                    case 'SPATIAL':
                        $sql .= ", ADD SPATIAL INDEX `$columnName` (`$columnName`)";
                        break;
                    default:
                        break;
                }
            }

            if ($conn->query($sql)) {
                header("Location: ../showTableDetails.php");
            } else {
                echo "Error in query: $conn->error";
                echo "<br><pre>$sql</pre>";
                echo "<a id='backAnchor' href='../showTableDetails.php'>Esc</a>";
            }
        } else {
            die("Column name and type are required.");
        }
    }

    ?>

    <script src="../js/backAnchor.js"></script>
</body>

</html>
