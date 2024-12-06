<?php
require_once "connection/connection.php";

Connection::setCredentials();
$conn = Connection::startConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tableName = $_SESSION["current_table"] ?? null;
    $columnName = $_POST["selectedColumn"] ?? null;
    $action = $_POST["action"] ?? null;

    if (!$tableName || !$columnName || !$action) {
        die("Missing required data.");
    }

    if ($action === "rename") {
        $newColumnName = $_POST["newColumnName"] ?? null;
        if (!$newColumnName) {
            die("New column name not provided.");
        }

        $query = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
        $result = $conn->query($query);
        if ($result) {
            $columnData = $result->fetch_assoc();
            $currentType = $columnData['Type'];

            $renameQuery = "ALTER TABLE $tableName CHANGE `$columnName` `$newColumnName` $currentType";
            if ($conn->query($renameQuery)) {
                header("Location: ./showTableDetails.php");
            } else {
                die("Error renaming column: $conn->error");
            }
        } else {
            die("Error fetching column data type: $conn->error");
        }
    } elseif ($action === "delete") {
        $deleteQuery = "ALTER TABLE $tableName DROP COLUMN `$columnName`";
        if ($conn->query($deleteQuery)) {
            header("Location: ./showTableDetails.php");
        } else {
            die("Error deleting column: $conn->error");
        }
    } else {
        die("Invalid action.");
    }
}
