<?php

$data = json_decode(file_get_contents('php://input'), true);

require '../utils/db.php';

$req_method = $_SERVER['REQUEST_METHOD'];

// check the action is POST
if ($req_method == 'POST') {
    
    $userId = $data['user_id'] ?? null;
    $products = $data['products'] ?? null;

    $totalPrice = 0;

    if( !$userId || $userId == null || empty($userId)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No user ID provided'
        ]);
        exit();
    } else if (!$products || $products == null || empty($products)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No products provided'
        ]);
        exit();
    } else {
        // Loop through the products and calculate the total price
        
        foreach ($products as $product) {
            $productId = $product['id'] ?? null;
            $quantity = $product['quantity'] ?? null;


            if (!$productId || $productId == null || empty($productId)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No product ID provided'
                ]);
                exit();
            } else if (!$quantity || $quantity == null || empty($quantity)) {
                $quantity = 1;
            } else {
                // Fetch the product price from the database
                $query = "SELECT `price` FROM products WHERE id = :id";
                $stmt = Database::search($query, [':id' => $productId]);

                if (count($stmt) == 0) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Product not found'
                    ]);
                    exit();
                } 

                $productPrice = $stmt[0]['price'];

                // Calculate the total price
                $totalPrice += $productPrice * $quantity;
            }
        }

        // Insert the transaction into the database
        $transactionId = uniqid();
        $query = "INSERT INTO transactions (id,total) VALUES (:transaction_id, :total_price)";
        $stmt = Database::iud($query, [':transaction_id' => $transactionId, ':total_price' => $totalPrice]);
        if (!$stmt) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create transaction'
            ]);
            exit();
        }

        foreach ($products as $product) {
            $productId = $product['id'] ?? null;
            $quantity = $product['quantity'] ?? null;

            if (!$productId || $productId == null || empty($productId)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No product ID provided'
                ]);
                exit();
            } else if (!$quantity || $quantity == null || empty($quantity)) {
                $quantity = 1;
            }

            // Insert the transaction details into the database
            $query = "INSERT INTO transactions_has_products (transactions_id,products_id,qty) VALUES (:transaction_id, :product_id, :quantity)";
            $stmt = Database::iud($query, [
                ':transaction_id' => $transactionId,
                ':product_id' => $productId,
                ':quantity' => $quantity
            ]);
            if (!$stmt) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create transaction details'
                ]);
                exit();
            }            
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Transaction created successfully',
            'transaction_id' => $transactionId,
            'total_price' => $totalPrice
        ]);

        exit();

    }

} else if ($req_method == 'PUT') {
    echo json_encode([
        'status' => 'success',
        'message' => 'This is a PUT request',
    ]);
    exit();
} else if ($req_method == 'GET') {
    echo json_encode([
        'status' => 'success',
        'message' => 'This is a GET request',
    ]);
    exit();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method',
    ]);
    exit();
}