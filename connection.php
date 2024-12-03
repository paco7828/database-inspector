<?php

session_start();

class Connection {
    private static $servername;
    private static $username;
    private static $password;
    private static $dbname;

    public static function setCredentials() {
        if (!isset($_SESSION['db_credentials'])) {
            if (isset($_POST["servernameInput"], $_POST["usernameInput"], $_POST["passwordInput"], $_POST["dbnameInput"])) {
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
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            self::$servername = $_SESSION['db_credentials']['servername'];
            self::$username = $_SESSION['db_credentials']['username'];
            self::$password = $_SESSION['db_credentials']['password'];
            self::$dbname = $_SESSION['db_credentials']['dbname'];
        }
    }

    public static function startConnection() {
        self::setCredentials();

        $conn = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);

        if ($conn->connect_error) {
            die("An error has occurred: $conn->connect_error");
        }
        return $conn;
    }
}

$conn = Connection::startConnection();
