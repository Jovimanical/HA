<?php
define("DATABASE_HOST", trim($_ENV["HA_APP_ENV"]) === "DEV" ? trim($_ENV["DEVELOPMENT_DATABASE_HOST"]) : trim($_ENV["PRODUCTION_DATABASE_HOST"]));
define("DATABASE_NAME", trim($_ENV["HA_APP_ENV"]) === "DEV" ? trim($_ENV["DEVELOPMENT_DATABASE"]) : trim($_ENV["PRODUCTION_DATABASE"]));
define("DATABASE_USERNAME", trim($_ENV["HA_APP_ENV"]) === "DEV" ? trim($_ENV["DEVELOPMENT_DATABASE_USER"]) : trim($_ENV["PRODUCTION_DATABASE_USER"]));
define("DATABASE_PASSWORD", trim($_ENV["HA_APP_ENV"]) === "DEV" ? trim($_ENV["DEVELOPMENT_DATABASE_PASSWORD"]) : trim($_ENV["PRODUCTION_DATABASE_PASSWORD"]));
define("DATABASE_PORT", trim($_ENV["HA_APP_ENV"]) === "DEV" ? trim($_ENV["DEVELOPMENT_DATABASE_PORT"]) : trim($_ENV["PRODUCTION_DATABASE_PORT"]));


class Database
{

    // specify your own database credentials
    private $host ;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    function __construct()
    {
        // Process the config file and dump the variables into $config
        $this->host = DATABASE_HOST;
        $this->db_name = DATABASE_NAME;
        $this->username = DATABASE_USERNAME;
        $this->password = DATABASE_PASSWORD;
        $this->port = DATABASE_PORT;

    }

    // get the database connection
    public function getConnection(): ?PDO
    {

        $this->conn = null;

        try {
            if ($this->port) {
                $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            } else {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            }

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");


        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}



?>
