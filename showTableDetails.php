<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Details</title>
    <link rel="stylesheet" href="style.css">
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
    require_once "connection.php";

    $tableName = $_GET['tableName'] ?? null;
    if (!$tableName) {
        die("Table name not specified");
    }

    // Dynamically retrieve the primary key
    $primaryKeyQuery = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
    $primaryKeyResult = $conn->query($primaryKeyQuery);

    if (!$primaryKeyResult || $primaryKeyResult->num_rows == 0) {
        die("Could not retrieve the primary key for table $tableName");
    }

    $primaryKey = $primaryKeyResult->fetch_assoc()['Column_name'];

    // Dynamically retrieve the foreign keys
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

    // Dynamically retrieve unique keys
    $uniqueKeyQuery = "SHOW KEYS FROM $tableName WHERE Non_unique = 0 AND Key_name != 'PRIMARY'";
    $uniqueKeyResult = $conn->query($uniqueKeyQuery);

    $uniqueKeys = [];
    if ($uniqueKeyResult && $uniqueKeyResult->num_rows > 0) {
        while ($ukRow = $uniqueKeyResult->fetch_assoc()) {
            $uniqueKeys[] = $ukRow['Column_name'];
        }
    }

    // Fetch all data from the table
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if (!$result) {
        die("An error has occurred: $conn->error");
    } else {
        $columns = $result->fetch_fields();

        echo "<table border='1'>
    <tr>";

        foreach ($columns as $col) {
            // Assign ID for primary key and class for foreign/unique keys
            $thAttributes = '';
            if ($col->name === $primaryKey) {
                $thAttributes = ' id="primaryKey"';
            } elseif (in_array($col->name, $foreignKeys)) {
                $thAttributes = ' class="foreignKey"';
            } elseif (in_array($col->name, $uniqueKeys)) {
                $thAttributes = ' class="uniqueKey"';
            }

            echo "<th$thAttributes>" . htmlspecialchars($col->name) . "</th>";
        }

        echo "<th>Update</th>
    <th>Delete</th>
    </tr>";

        while ($row = $result->fetch_assoc()) {
            $rowId = $row[$primaryKey];

            echo "<tr>
        <form method='POST' action='updateRow.php'>
            <input type='hidden' name='row_id' value='" . htmlspecialchars($rowId) . "'>
            <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>";

            foreach ($row as $key => $val) {
                echo "<td><input type='text' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($val) . "'></td>";
            }

            echo "<td><input type='submit' value='Confirm changes' name='update_row'></td>
        </form>
        <td>
            <form method='POST' action='deleteRow.php' onsubmit='return confirm(\"Are you sure you want to delete this row?\");'>
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

    <form action="addColumn.php">
        <input type="text" name="nameInput" id="nameInput" placeholder="Column name...">
        <label for="typeSelect">Type</label>
        <select name="typeSelect" id="typeSelect">
            <option value="" selected>INT</option>
            <option value="">VARCHAR</option>
            <option value="">TEXT</option>
            <option value="">DATE</option>
            <option value="">TINYINT</option>
            <option value="">SMALLINT</option>
            <option value="">MEDIUMINT</option>
            <option value="">BIGINT</option>
            <option value="">DECIMAL</option>
            <option value="">FLOAT</option>
            <option value="">DOUBLE</option>
            <option value="">REAL</option>
            <option value="">BIT</option>
            <option value="">BOOLEAN</option>
            <option value="">SERIAL</option>
            <option value="">DATETIME</option>
            <option value="">TIMESTAMP</option>
            <option value="">TIME</option>
            <option value="">YEAR</option>
            <option value="">CHAR</option>
            <option value="">TINYTEXT</option>
            <option value="">MEDIUMTEXT</option>
            <option value="">LONGTEXT</option>
            <option value="">BINARY</option>
            <option value="">VARBINARY</option>
            <option value="">TINYBLOB</option>
            <option value="">BLOB</option>
            <option value="">MEDIUMBLOB</option>
            <option value="">LONGBLOB</option>
            <option value="">ENUM</option>
            <option value="">SET</option>
            <option value="">GEOMETRY</option>
            <option value="">POINT</option>
            <option value="">LINESTRING</option>
            <option value="">POLYGON</option>
            <option value="">MULTIPOINT</option>
            <option value="">MULTILINESTRING</option>
            <option value="">MULTIPOLYGON</option>
            <option value="">GEOMETRYCOLLECTION</option>
            <option value="">JSON</option>
        </select>
        <input type="number" id="lengthInput" name="lengthInput" placeholder="Length...">
        <label for="defaultSelect">Default value</label>
        <select name="defaultSelect" id="defaultSelect">
            <option value="" selected>Nothing</option>
            <option value="">NULL</option>
            <option value="">CURRENT_TIMESTAMP</option>
        </select>
        <select name="charsetSelect" id="charsetSelect">
            <option value="" selected disabled>Character set</option>
            <option value="">utf8_hungarian_ci</option>
            <option value="">armscii8_bin</option>
            <option value="">armscii8_general_ci</option>
            <option value="">armscii8_general_nopad_ci</option>
            <option value="">armscii8_nopad_bin</option>
        </select>
        <select name="propertiesSelect" id="propertiesSelect">
            <option value="" selected disabled>Properties</option>
            <option value="">BINARY</option>
            <option value="">UNSIGNED</option>
            <option value="">UNSIGNED ZEROFILL</option>
            <option value="">on update CURRENT_TIMESTAMP</option>
            <option value="">COMPRESSED=zlib</option>
        </select>
        <label for="isNullInput">Null?</label>
        <input type="checkbox" id="isNullCheckbox" name="isNullInput">
        <label for="indexSelect">Index</label>
        <select name="indexSelect" id="indexSelect">
            <option value="" selected disabled>---</option>
            <option value="">PRIMARY</option>
            <option value="">UNIQUE</option>
            <option value="">INDEX</option>
            <option value="">FULLTEXT</option>
            <option value="">SPATIAL</option>
        </select>
        <label for="autoIncCheckbox">Auto increment</label>
        <input type="checkbox" id="autoIncCheckbox" name="autoIncCheckbox">
        <!--  ADD REMAINING INPUTS -->
        <input type="submit" value="Add new column">
    </form>

    <a id="backAnchor" href="connect.php">Back</a>
    <script src="backAnchor.js"></script>

    <script>
        const inputs = document.querySelectorAll("input:not([type='hidden']):not([type='submit'])");
        const backAnchor = document.querySelector("#backAnchor");
        let index = 0;
        let isEditing = false;
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
                                    index += 3;
                                    break;
                                case "ArrowUp":
                                    index -= 3;
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