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
    </style>
</head>

<body>
    <?php
    require_once "connection.php";

    $tableName = $_GET['tableName'] ?? null;
    if (!$tableName) {
        die("Table name not specified");
    }

    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if (!$result) {
        die("An error has occurred: $conn->error");
    } else {
        echo "<table border='1'>
          <tr>";
        $columns = $result->fetch_fields();
        foreach ($columns as $col) {
            echo "<th>" . htmlspecialchars($col->name) . "</th>";
        }
        echo "
        <th>Update</th>
        <th>Delete</th>
        </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <form method='POST' action='updateRow.php'>
                  <input type='hidden' name='row_id' value='" . htmlspecialchars($row['id']) . "'>
                  <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>";
            foreach ($row as $key => $val) {
                echo "<td><input type='text' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($val) . "'></td>";
            }
            echo "<td><input type='submit' value='Confirm changes' name='update_row'></td>
              </form>
              <td>
              <form method='POST' action='deleteRow.php' onsubmit='return confirm(\"Are you sure you want to delete this row?\");'>
                  <input type='hidden' name='row_id' value='" . htmlspecialchars($row['id']) . "'>
                  <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>
                  <input type='submit' value='Delete' name='delete_row'>
              </form>
              </td>
              </tr>";
        }
        echo "</table>";
    }
    echo "<a id='backAnchor' href='connect.php'>Back</a>";
    ?>
    <script src="backAnchor.js"></script>
    <script>
        const inputs = document.querySelectorAll("input:not([type='hidden']):not([type='submit'])");
        const backAnchor = document.querySelector("#backAnchor");
        let index = 0;
        let isEditing = false;
        inputs[0].focus();

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
    </script>
</body>

</html>