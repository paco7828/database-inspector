<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Details</title>
    <link rel="stylesheet" href="tableDetailStyles.css">
</head>

<body>
    <?php
    require_once "connection.php";

    $tableName = isset($_GET['tableName']) ? $_GET['tableName'] : null;
    if (!$tableName) {
        die("Table name not specified");
    }

    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_row'])) {
        $id = intval($_POST['row_id']);
        $updates = [];

        foreach ($_POST as $key => $value) {
            if ($key !== 'update_row' && $key !== 'row_id' && $key !== 'tableNameInput') {
                $updates[] = "$key='" . $conn->real_escape_string($value) . "'";
            }
        }
        $updateSQL = "UPDATE $tableName SET " . implode(', ', $updates) . " WHERE id=$id";
        if ($conn->query($updateSQL)) {
            header("Location: showTableDetails.php?tableName=" . urlencode($tableName));
            exit;
        } else {
            echo "<p>Error updating row: $conn->error </p>";
        }
    }

    if (!$result) {
        die("An error has occurred: $conn->error");
    } else {
        echo "<table border='1'>
              <tr>";
        $columns = $result->fetch_fields();
        foreach ($columns as $col) {
            echo "<th>" . htmlspecialchars($col->name) . "</th>";
        }
        echo "<th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                  <form method='POST'>
                      <input type='hidden' name='row_id' value='" . htmlspecialchars($row['id']) . "'>
                      <input type='hidden' name='tableNameInput' value='" . htmlspecialchars($tableName) . "'>";
            foreach ($row as $key => $val) {
                echo "<td><input type='text' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($val) . "'></td>";
            }
            echo "<td><button type='submit' name='update_row'>Submit</button></td>
                  </form>
                  </tr>";
        }
        echo "</table>";
    }
    echo "<a href='connect.php'>Back</a>";
    ?>
</body>

</html>
