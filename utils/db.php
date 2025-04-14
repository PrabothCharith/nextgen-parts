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
        try {
            $db = new Database();
            return $db->conn;
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ]);
            exit();
        }
    }

    public static function closeConnection()
    {
        try {
            $db = new Database();
            $db->conn = null;
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to close database connection',
                'error' => $e->getMessage()
            ]);
            exit();
        }
    }
    public static function iud($query, $params = [])
    {
        try {
            $db = new Database();
            $stmt = $db->conn->prepare($query);
            $stmt->execute($params);
            $db->closeConnection();
            return $stmt;
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database query failed',
                'error' => $e->getMessage()
            ]);
            exit();
        }
    }
}