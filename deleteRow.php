<?php

require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_row'])) {
    $id = intval($_POST['row_id']);
    $tableName = $_POST['tableNameInput'];

    $deleteSQL = "DELETE FROM $tableName WHERE id = $id";

    if ($conn->query($deleteSQL)) {
        header("Location: showTableDetails.php?tableName=" . urlencode($tableName));
        exit;
    } else {
        echo "<p>Error deleting row: $conn->error </p>";
    }
}