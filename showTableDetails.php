<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Details</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        table,
        th,
        tr,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        #primaryKey {
            background-color: yellow;
        }

        .foreignKey {
            background-color: gray;
        }

        .uniqueKey {
            background-color: cyan;
        }
    </style>
</head>

<body>
    <?php
    require_once "connection/connection.php";

    Connection::setCredentials();

    $conn = Connection::startConnection();

    if (isset($_GET["tableName"])) {
        $_SESSION["current_table"] = $_GET["tableName"];
    }

    $tableName = $_SESSION["current_table"] ?? null;

    if (!$tableName) {
        die("Table name not provided.");
    }

    $primaryKeyQuery = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
    $primaryKeyResult = $conn->query($primaryKeyQuery);

    $primaryKey = $primaryKeyResult->fetch_assoc()['Column_name'] ?? null;

    $foreignKeyQuery = "
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = '$tableName' 
      AND TABLE_SCHEMA = DATABASE() 
      AND REFERENCED_TABLE_NAME IS NOT NULL";
    $foreignKeyResult = $conn->query($foreignKeyQuery);

    $foreignKeys = [];
    if ($foreignKeyResult && $foreignKeyResult->num_rows > 0) {
        while ($fkRow = $foreignKeyResult->fetch_assoc()) {
            $foreignKeys[] = $fkRow['COLUMN_NAME'];
        }
    }

    $uniqueKeyQuery = "SHOW KEYS FROM $tableName WHERE Non_unique = 0 AND Key_name != 'PRIMARY'";
    $uniqueKeyResult = $conn->query($uniqueKeyQuery);

    $uniqueKeys = [];
    if ($uniqueKeyResult && $uniqueKeyResult->num_rows > 0) {
        while ($ukRow = $uniqueKeyResult->fetch_assoc()) {
            $uniqueKeys[] = $ukRow['Column_name'];
        }
    }

    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if (!$result) {
        die("An error has occurred: $conn->error");
    } else {
        $columns = $result->fetch_fields();

        echo "<table border='1'>
        <tr>";

        foreach ($columns as $col) {
            $thAttributes = '';
            if ($col->name === $primaryKey) {
                $thAttributes = 'id="primaryKey"';
            } elseif (in_array($col->name, $foreignKeys)) {
                $thAttributes = 'class="foreignKey"';
            } elseif (in_array($col->name, $uniqueKeys)) {
                $thAttributes = 'class="uniqueKey"';
            }

            echo "<th $thAttributes>" . htmlspecialchars($col->name) . "</th>";
        }

        echo "<th>Update</th>
        <th>Delete</th>
        </tr>";

        while ($row = $result->fetch_assoc()) {
            $rowId = $row[$primaryKey];

            echo "<tr>
            <form method='POST' action='create/updateRow.php'>
                <input type='hidden' name='row_id' value='" . htmlspecialchars($rowId) . "'>
                <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>";

            foreach ($row as $key => $val) {
                echo "<td><input type='text' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($val) . "'></td>";
            }

            echo "<td><input type='submit' value='Confirm changes' name='update_row'></td>
            </form>
            <td>
            <form method='POST' action='delete/deleteRow.php' onsubmit='return confirm(\"Are you sure you want to delete this row?\");'>
                <input type='hidden' name='row_id' value='" . htmlspecialchars($rowId) . "'>
                <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>
                <input type='submit' value='Delete' name='delete_row'>
            </form>
        </td>
        </tr>";
        }

        echo "</table>";
    }
    ?>

    <form method="POST" action="create/addColumn.php">
        <input type="text" name="nameInput" id="nameInput" placeholder="Column name...">
        <select name="typeSelect" id="typeSelect">
            <option value="INT">INT</option>
            <option value="VARCHAR">VARCHAR</option>
            <option value="TEXT">TEXT</option>
            <option value="DATE">DATE</option>
            <option value="TINYINT">TINYINT</option>
            <option value="SMALLINT">SMALLINT</option>
            <option value="MEDIUMINT">MEDIUMINT</option>
            <option value="BIGINT">BIGINT</option>
            <option value="DECIMAL">DECIMAL</option>
            <option value="FLOAT">FLOAT</option>
            <option value="DOUBLE">DOUBLE</option>
            <option value="REAL">REAL</option>
            <option value="BIT">BIT</option>
            <option value="BOOLEAN">BOOLEAN</option>
            <option value="SERIAL">SERIAL</option>
            <option value="DATETIME">DATETIME</option>
            <option value="TIMESTAMP">TIMESTAMP</option>
            <option value="TIME">TIME</option>
            <option value="YEAR">YEAR</option>
            <option value="CHAR">CHAR</option>
            <option value="TINYTEXT">TINYTEXT</option>
            <option value="MEDIUMTEXT">MEDIUMTEXT</option>
            <option value="LONGTEXT">LONGTEXT</option>
            <option value="BINARY">BINARY</option>
            <option value="VARBINARY">VARBINARY</option>
            <option value="TINYBLOB">TINYBLOB</option>
            <option value="BLOB">BLOB</option>
            <option value="MEDIUMBLOB">MEDIUMBLOB</option>
            <option value="LONGBLOB">LONGBLOB</option>
            <option value="ENUM">ENUM</option>
            <option value="SET">SET</option>
            <option value="GEOMETRY">GEOMETRY</option>
            <option value="POINT">POINT</option>
            <option value="LINESTRING">LINESTRING</option>
            <option value="POLYGON">POLYGON</option>
            <option value="MULTIPOINT">MULTIPOINT</option>
            <option value="MULTILINESTRING">MULTILINESTRING</option>
            <option value="MULTIPOLYGON">MULTIPOLYGON</option>
            <option value="GEOMETRYCOLLECTION">GEOMETRYCOLLECTION</option>
            <option value="JSON">JSON</option>

        </select>
        <input type="number" id="lengthInput" name="lengthInput" placeholder="Length...">
        <label for="defaultSelect">Default value</label>
        <select name="defaultSelect" id="defaultSelect">
            <option value="" selected>Nothing</option>
            <option value="NULL">NULL</option>
            <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
        </select>
        <select name="collationSelect" id="collationSelect">
            <option value="" selected>Collation</option>
            <option value="utf8_hungarian_ci">utf8_hungarian_ci</option>
            <option value="armscii8_bin">armscii8_bin</option>
            <option value="armscii8_general_ci">armscii8_general_ci</option>
            <option value="armscii8_general_nopad_ci">armscii8_general_nopad_ci</option>
            <option value="armscii8_nopad_bin">armscii8_nopad_bin</option>
        </select>
        <label for="isNullCheckbox">Null?</label>
        <input type="checkbox" id="isNullCheckbox" name="isNullCheckbox">
        <label for="indexSelect">Index</label>
        <select name="indexSelect" id="indexSelect">
            <option value="" selected>---</option>
            <option value="PRIMARY">PRIMARY</option>
            <option value="UNIQUE">UNIQUE</option>
            <option value="INDEX">INDEX</option>
            <option value="FULLTEXT">FULLTEXT</option>
            <option value="SPATIAL">SPATIAL</option>
        </select>
        <label for="autoIncCheckbox">Auto increment</label>
        <input type="checkbox" id="autoIncCheckbox" name="autoIncCheckbox">
        <textarea name="commentInput" id="commentInput" placeholder="Comment..." cols="10" rows="5"></textarea>
        <input type="submit" value="Add new column">
    </form>

    <form action="delete/deleteColumn.php" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this column?')">
        <select name="delColumn" id="delColumn">
            <option value="" selected>Select column name</option>
            <?php
            $columnNamesQuery = "SHOW COLUMNS FROM $tableName";
            $columnQueryResult = $conn->query($columnNamesQuery);
            if (!$columnQueryResult) {
                die("Error has occured while fetching column names: $conn->error");
            }
            while ($row = $columnQueryResult->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row["Field"]) . "'>" . htmlspecialchars($row["Field"]) . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Delete">
    </form>

    <a id="backAnchor" href="./connect.php">Back</a>
    <script src="js/backAnchor.js"></script>

    <script>
        const inputs = document.querySelectorAll("input:not([type='hidden']):not([type='submit']), select, textarea");
        let index = 0;
        let isEditing = false;

        const table = document.querySelector("table");
        const rows = table.querySelectorAll("tr");
        const firstRowCells = rows[0].querySelectorAll("th");
        const columnCount = firstRowCells.length - 2;

        if (inputs.length) {
            inputs[index].focus();

            document.addEventListener("keydown", (e) => {
                switch (e.key) {
                    case "Control":
                        isEditing = !isEditing;
                        break;
                    default:
                        if (!isEditing) {
                            switch (e.key) {
                                case "ArrowDown":
                                    index += columnCount;
                                    break;
                                case "ArrowUp":
                                    index -= columnCount;
                                    break;
                                case "ArrowLeft":
                                    index--;
                                    break;
                                case "ArrowRight":
                                    index++;
                                    break;
                            }

                            if (index >= inputs.length) {
                                index = 0;
                            } else if (index < 0) {
                                index = inputs.length - 1;
                            }

                            inputs[index].focus();
                        }
                        break;
                }
            });
        }
    </script>

</body>

</html>