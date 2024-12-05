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
        if (isset($_POST["servernameInput"], $_POST["usernameInput"])) {
            if (empty($_POST["servernameInput"]) || empty($_POST["usernameInput"])) {
                $_SESSION['error_message'] = "Server name and username are required!";
                return;
            }

            self::$servername = htmlspecialchars($_POST["servernameInput"]);
            self::$username = htmlspecialchars($_POST["usernameInput"]);
            self::$password = isset($_POST["passwordInput"]) ? htmlspecialchars($_POST["passwordInput"]) : '';

            $_SESSION['db_credentials'] = [
                'servername' => self::$servername,
                'username' => self::$username,
                'password' => self::$password
            ];
        } elseif (isset($_SESSION['db_credentials'])) {
            self::$servername = $_SESSION['db_credentials']['servername'] ?? '';
            self::$username = $_SESSION['db_credentials']['username'] ?? '';
            self::$password = $_SESSION['db_credentials']['password'] ?? '';
        } else {
            header("Location: index.php");
            exit;
        }
    }

    public static function setDatabaseName($dbname)
    {
        self::$dbname = htmlspecialchars($dbname);

        $_SESSION['db_credentials']['dbname'] = self::$dbname;
    }

    public static function startConnection()
    {
        self::setCredentials();

        if (empty(self::$dbname)) {
            echo "<div>Database name is missing. ";
            echo "<a href='index.php'>Back</a></div>";
            die();
        }

        $conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);

        if ($conn->connect_error) {
            echo "<div>An error has occurred: $conn->connect_error. ";
            echo "<a href='index.php'>Back</a></div>";
            die();
        }

        return $conn;
    }
}
