<?php
require '../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


// Make a database connection as a class to use in other files
class Database{
    private $conn;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public static function getConnection()
    {
        $db = new Database();
        return $db->conn;
    }

    public static function closeConnection()
    {
        $db = new Database();
        $db->conn = null;
    }
    public static function iud($query, $params = [])
    {
        $db = new Database();
        $stmt = $db->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
}