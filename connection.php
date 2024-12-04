<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    session_start();

    class Connection
    {
        private static $servername;
        private static $username;
        private static $password;
        private static $dbname;

        public static function setCredentials()
        {
            if (isset($_POST["servernameInput"], $_POST["usernameInput"], $_POST["passwordInput"], $_POST["dbnameInput"])) {
                if (empty($_POST["servernameInput"]) || empty($_POST["usernameInput"]) || empty($_POST["passwordInput"]) || empty($_POST["dbnameInput"])) {
                    $_SESSION['error_message'] = "All fields are required!";
                    return;
                }

                self::$servername = htmlspecialchars($_POST["servernameInput"]);
                self::$username = htmlspecialchars($_POST["usernameInput"]);
                self::$password = htmlspecialchars($_POST["passwordInput"]);
                self::$dbname = htmlspecialchars($_POST["dbnameInput"]);

                $_SESSION['db_credentials'] = [
                    'servername' => self::$servername,
                    'username' => self::$username,
                    'password' => self::$password,
                    'dbname' => self::$dbname
                ];
            } elseif (isset($_SESSION['db_credentials'])) {
                self::$servername = $_SESSION['db_credentials']['servername'];
                self::$username = $_SESSION['db_credentials']['username'];
                self::$password = $_SESSION['db_credentials']['password'];
                self::$dbname = $_SESSION['db_credentials']['dbname'];
            } else {
                header("Location: index.php");
                exit;
            }
        }

        public static function startConnection()
        {
            self::setCredentials();

            if (empty(self::$servername) || empty(self::$username) || empty(self::$password) || empty(self::$dbname)) {
                echo "<div>Database credentials are missing or invalid. ";
                echo "<a id='backAnchor' href='index.php'>Back</a></div>";
                die();
            }

            $conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);

            if ($conn->connect_error) {
                echo "<div>An error has occurred: $conn->connect_error. ";
                echo "<a id='backAnchor' href='index.php'>Back</a></div>";
                die();
            }
            return $conn;
        }
    }

    $conn = Connection::startConnection();
    ?>
    <script src="backAnchor.js"></script>
</body>

</html>
