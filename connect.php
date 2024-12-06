<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available tables</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        #container {
            text-align: center;
            margin-top: 15%;
        }

        #tablesDiv {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
    </style>
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
                      <label>
                          <input type='radio' name='action' value='connect' required> Connect to table
                      </label>
                      <label>
                          <input type='radio' name='action' value='rename'> Rename table
                      </label>
                      <label>
                          <input type='radio' name='action' value='delete'> Delete table
                      </label>
                  </div>
                  <input type='submit' value='Submit'>
              </form>
              </div>";
        ?>
        <a id='backAnchor' href='./userPage.php'>Back</a>
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

        <!-- <script> 
            document.addEventListener("DOMContentLoaded", () => {
                const tableBtns = document.querySelectorAll("input[type='submit']");
                let index = 0;
                highlight(index);

                document.addEventListener("keydown", (e) => {
                    switch (e.key) {
                        case "ArrowRight":
                            index++;
                            break;
                        case "ArrowLeft":
                            index--;
                            break;
                        case "Enter":
                            tableBtns[index].form.submit();
                            break;
                    }

                    if (index < 0) {
                        index = tableBtns.length - 1;
                    } else if (index > tableBtns.length - 1) {
                        index = 0;
                    }

                    highlight(index);
                });

                function highlight(index) {
                    let colors = ["white", "green"];

                    tableBtns.forEach(btn => {
                        btn.style.color = "";
                        btn.style.backgroundColor = "";
                    });

                    tableBtns[index].style.color = colors[0];
                    tableBtns[index].style.backgroundColor = colors[1];
                }
            });
        </script>
        -->
    </div>
</body>

</html>