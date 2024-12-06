<?php

$existingDatabases = [];
$sql = "SHOW DATABASES";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        array_push($existingDatabases, $row["Database"]);
    }
}

echo "<option value=''>Select database</option>";
foreach ($existingDatabases as $db) {
    echo "<option value='$db'>$db</option>";
}
