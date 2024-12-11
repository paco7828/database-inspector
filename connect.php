<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available tables</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/radio.css">
    <link rel="stylesheet" href="styles/backAnchor.css">
</head>

<body>
    <div id="container">
        <?php
        require_once "connection/connection.php";

        if (isset($_POST['dbnameInput'])) {
            $_SESSION['dbname'] = $_POST['dbnameInput'];
        } elseif (isset($_SESSION['dbname'])) {
            $_POST['dbnameInput'] = $_SESSION['dbname'];
        } else {
            die("Database name is not provided.");
        }

        Connection::setCredentials();
        Connection::setDatabaseName($_SESSION['dbname']);

        $servername = $_SESSION["db_credentials"]["servername"];
        $username = $_SESSION["db_credentials"]["username"];
        $password = $_SESSION["db_credentials"]["password"];
        $dbname = $_SESSION["db_credentials"]["dbname"];

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: $conn->connect_error");
        }

        $sql = "SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = '$dbname'";
        $result = $conn->query($sql);

        if (!$result) {
            die("An error has occurred: $conn->error");
        }

        echo "<h1>Choose a table</h1>
              <div id='tablesDiv'>
              <form action='' method='POST'>
                  <select name='tableNameInput' required>";

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row["table_name"]) . "'>" . htmlspecialchars($row["table_name"]) . "</option>";
        }

        echo "  </select>
                  <div id='actionButtons'>
                    <div>
                        <label for='connectRadio'>Connect to table</label>
                        <input id='connectRadio' type='radio' name='action' value='connect' required>
                    </div>
                    <div>
                        <label for='renameRadio'>Rename table</label>
                        <input id='renameRadio' type='radio' name='action' value='rename'>
                    </div>
                    <div>
                        <label for='deleteRadio'>Delete table</label>
                        <input id='deleteRadio' type='radio' name='action' value='delete'>
                    </div>
                  </div>
                  <input type='submit' value='Submit'>
              </form>
              </div>";
        ?>
        <a id='backAnchor' href='./userPage.php'>Esc</a>
        <script src="js/backAnchor.js"></script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['tableNameInput']) && isset($_POST['action'])) {
                $tableName = $_POST['tableNameInput'];
                $action = $_POST['action'];
                switch ($action) {
                    case 'connect':
                        header("Location: ./showTableDetails.php?tableName=" . urlencode($tableName));
                        exit;
                    case 'rename':
                        header("Location: create/renameTable.php?tableName=" . urlencode($tableName));
                        exit;
                    case 'delete':
                        header("Location: delete/deleteTable.php?tableName=" . urlencode($tableName));
                        exit;
                    default:
                        echo "<p style='color: red;'>Invalid action selected!</p>";
                }
            }
        }
        ?>
    </div>
</body>

</html>