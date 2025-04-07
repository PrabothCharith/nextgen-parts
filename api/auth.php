<?php
require '../vendor/autoload.php';
require './utils/JwtHelper.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($_GET['t']) && $_GET['t'] == 'v') {
    // Get Bearer Token From Authorization Headers
    $jwt = null;

    // Method 1
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $matches = [];
        preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
        if (count($matches) > 1) {
            $jwt = $matches[1];
        }
    }

    // Method 2
    // $headers = apache_request_headers();
    // if (isset($headers['Authorization'])) {
    //     $matches = [];
    //     preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches);
    //     if (count($matches) > 1) {
    //         $jwt = $matches[1];
    //     }
    // }

    if ($jwt == null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No token provided'
        ]);
        exit();
    }

    // Decode the token
    $jwtHelper = new JwtHelper();
    $decoded = $jwtHelper->decodeToken($jwt);

    if (!$decoded->exp || !$decoded->iat || !$decoded->data) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid token'
        ]);
        exit();
    }

    // Check if the token is expired
    if ($decoded->exp < time()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Token expired'
        ]);
        exit();
    }

    // Check if the token is valid
    if ($decoded->iat > time()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Token not yet valid',
            'token' => $jwt,
            'data' => $decoded->data
        ]);
        exit();
    }

    // Send valid data to client side
    if ($decoded->data->email != null && $decoded->data->id != null) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Token is valid',
            'token' => $jwt,
            'data' => $decoded
        ]);
        exit();
    }
} else if (isset($data['email']) && isset($data['password']) && isset($_GET['t'])) {
    $type = $_GET['t'];
    $email = $data['email'];
    $password = $data['password'];

    $db_name = $_ENV['DB_NAME'];
    $db_user = $_ENV['DB_USER'];
    $db_pass = $_ENV['DB_PASSWORD'];
    $db_host = $_ENV['DB_HOST'];
    $db_port = $_ENV['DB_PORT'];

    try {
        $conn = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // check is register or login
        // r = register
        // l = login
        if ($type == 'r') {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // check if user already exists
            if (count($user) == 0) {
                $stmt = $conn->prepare("INSERT INTO users (`email`, `password`,`user_types_id`) VALUES (:email, :password, 2)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));

                // check if user is registered
                if ($stmt->execute()) {

                    // send response to client side
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'User registered successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User registration failed'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User already exists'
                ]);
            }
        } else if ($type == 'l') {
            $stmt = $conn->prepare("SELECT `id`,`password`,`status` FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $jwtHelper = new JwtHelper();

            // check if user exists
            if (count($user) == 1) {

                // check if password is correct
                if (password_verify($password, $user[0]['password'])) {

                    // check if user is active
                    if ($user[0]['status'] == 0) {
                        echo json_encode([
                            'status' => 'warning',
                            'message' => 'Your account is not active. Please contact the admin.'
                        ]);
                        exit();
                    }

                    // jwt token data
                    $user_data = [
                        'email' => $email,
                        'id' => $user[0]['id']
                    ];

                    // generate jwt token
                    $token = $jwtHelper->generateToken($user_data);

                    // send response to client side
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'User logged in successfully',
                        'token' => $token,
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Invalid password'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User not found'
                ]);
            }
        }

        // close connection
        $conn = null;
    } catch (PDOException $e) {
        // if database connection failed
        // send response to client side
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input'
    ]);
}
