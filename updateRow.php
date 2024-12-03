<?php

require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_row'])) {
    $id = intval($_POST['row_id']);
    $tableName = $_POST['tableNameInput'];
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