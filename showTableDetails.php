<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Details</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/select.css">
    <link rel="stylesheet" href="styles/radio.css">
    <link rel="stylesheet" href="styles/table.css">
    <link rel="stylesheet" href="styles/backAnchor.css">
    <style>
        #container{
            display: flex;
            padding-top: 5%;
        }

        #addColForm{
            position: absolute;
            right: 10%;
            bottom: 5%;
            display: grid;
            justify-content: center;
            align-items: center;
        }

        #addColForm input[type="submit"]{
            margin-top: 5%;
        }

        #columnActionForm{
            position: absolute;
            right: 10%;
            top: 1%;
        }

        hr{
            width: 25vh;
            margin-top: 5%;
        }

        #renameField{
            position: absolute;
            top: 25%;
            right: -50%;
        }
    </style>
</head>

<body>
    <div id="container">
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

    // Check for primary key
    $primaryKeyQuery = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
    $primaryKeyResult = $conn->query($primaryKeyQuery);
    $primaryKey = null;
    if ($primaryKeyResult && $primaryKeyResult->num_rows > 0) {
        $primaryKeyRow = $primaryKeyResult->fetch_assoc();
        $primaryKey = $primaryKeyRow['Column_name'] ?? null;
    }

    // Check for foreign keys
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

    // Check for unique keys
    $uniqueKeyQuery = "SHOW KEYS FROM $tableName WHERE Non_unique = 0 AND Key_name != 'PRIMARY'";
    $uniqueKeyResult = $conn->query($uniqueKeyQuery);
    $uniqueKeys = [];
    if ($uniqueKeyResult && $uniqueKeyResult->num_rows > 0) {
        while ($ukRow = $uniqueKeyResult->fetch_assoc()) {
            $uniqueKeys[] = $ukRow['Column_name'];
        }
    }

    // Fetch table data
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
            $rowId = $primaryKey ? $row[$primaryKey] : reset($row);
            echo "<tr>
            <form method='POST' action='create/updateRow.php'>
                <input type='hidden' name='row_id' value='" . htmlspecialchars($rowId) . "'>
                <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>";

            foreach ($row as $key => $val) {
                echo "<td><input type='text' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($val) . "'></td>";
            }

            echo "<td><input type='submit' value='Confirm' name='update_row'></td>
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

    <form method="POST" action="create/addColumn.php" id="addColForm">
        <input type="text" name="nameInput" id="nameInput" placeholder="Column name...">
        <div>
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
        </div>
        <div>
            <label for="defaultSelect">Default value</label>
            <select name="defaultSelect" id="defaultSelect">
                <option value="" selected>Nothing</option>
                <option value="NULL">NULL</option>
                <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
            </select>
        </div>
        <select name="collationSelect" id="collationSelect">
            <option value="" selected>Collation</option>
            <option value="utf8_hungarian_ci">utf8_hungarian_ci</option>
            <option value="armscii8_bin">armscii8_bin</option>
            <option value="armscii8_general_ci">armscii8_general_ci</option>
            <option value="armscii8_general_nopad_ci">armscii8_general_nopad_ci</option>
            <option value="armscii8_nopad_bin">armscii8_nopad_bin</option>
        </select>
        <div>
            <label for="isNullCheckbox">Null?</label>
            <input type="checkbox" id="isNullCheckbox" name="isNullCheckbox">
        </div>
        <div>
            <label for="indexSelect">Index</label>
            <select name="indexSelect" id="indexSelect">
                <option value="" selected>---</option>
                <option value="PRIMARY">PRIMARY</option>
                <option value="UNIQUE">UNIQUE</option>
                <option value="INDEX">INDEX</option>
                <option value="FULLTEXT">FULLTEXT</option>
                <option value="SPATIAL">SPATIAL</option>
            </select>
        </div>
        <div>
            <label for="autoIncCheckbox">Auto increment</label>
            <input type="checkbox" id="autoIncCheckbox" name="autoIncCheckbox">
        </div>
        <textarea name="commentInput" id="commentInput" placeholder="Comment..." cols="10" rows="5"></textarea>
        <input type="submit" value="Add col!">
    </form>

    <form action="columnAction.php" method="POST" id="columnActionForm">
        <select name="selectedColumn" id="selectedColumn">
            <option value="" selected>Select column name</option>
            <?php
            $columnNamesQuery = "SHOW COLUMNS FROM $tableName";
            $columnQueryResult = $conn->query($columnNamesQuery);
            if (!$columnQueryResult) {
                die("Error has occurred while fetching column names: $conn->error");
            }
            while ($row = $columnQueryResult->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row["Field"]) . "'>" . htmlspecialchars($row["Field"]) . "</option>";
            }
            ?>
        </select>
        <div id="actionButtons">
            <div>
                <label for="renameRadio">Rename</label>
                <input type="radio" name="action" value="rename" id="renameRadio" required>
            </div>
            <div>
                <label for="deleteRadio">Delete</label>
                <input type="radio" name="action" value="delete" id="deleteRadio" required>
            </div>
        </div>
        <div id="renameField" style="display: none;">
            <input type="text" name="newColumnName" placeholder="New column name">
        </div>

        <div id="keyOptions" style="display: none;">
            <select name="keyType" id="keyType">
                <option value="" selected>Select key type</option>
                <option value="UNIQUE">Unique Key</option>
                <option value="INDEX">Index</option>
                <option value="FULLTEXT">Fulltext</option>
                <option value="SPATIAL">Spatial</option>
            </select>
        </div>

        <input type="submit" value="Submit!">
        <hr>
    </form>

    <a id="backAnchor" href="./connect.php">Esc</a>
    </div>
    <script src="js/backAnchor.js"></script>

    <script>
        const actionRadios = document.querySelectorAll("input[name='action']");
        const renameField = document.getElementById("renameField");

        actionRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "rename") {
                    renameField.style.display = "block";
                } else if (radio.value === "addOtherKey") {
                    renameField.style.display = "none";
                } else {
                    renameField.style.display = "none";
                }
            });
        });
    </script>

</body>

</html>