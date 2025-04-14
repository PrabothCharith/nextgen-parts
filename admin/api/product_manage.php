<?php

if (!isset($_GET['t']) || empty($_GET['t'])) {
    json_encode([
        'status' => 'error',
        'message' => 'No action provided'
    ]);
    exit();
}

$action = $_GET['t'];
$data = json_decode(file_get_contents('php://input'), true);

require '../../utils/db.php';

if ($action == 'f') {

    $productId = $_GET['id'] ?? null;

    if ($productId) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = Database::search($query, [':id' => $productId]);
    } else {
        // Fetch all products
        $query = "SELECT * FROM products";
        $stmt = Database::search($query);
    }

    if (count($stmt) == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No products found'
        ]);
        exit();
    } else if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch products',
        ]);
        exit();
    }
    
    // Decode the images from JSON
    $stmt = array_map(function ($product) {
        $product['images'] = json_decode($product['images'], true);
        return $product;
    }, $stmt);

    echo json_encode([
        'status' => 'success',
        'message' => 'Products fetched successfully',
        'data' => $stmt
    ]);
} else {
    if (!$data || $data == null || empty($data)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No data provided'
        ]);
        exit();
    } else{
        if ($action == 'i') {

            $name = $data['name'];
            $description = $data['description'];
            $price = $data['price'];
            $images = $data['images'];

            $uploadedImages = [];

            foreach ($images as $image) {
                // Image path and name generation
                $imageName = uniqid();
                $imageExtension = explode('data:image/', $image)[1];
                $imageExtension = explode(';', $imageExtension)[0];
                $imagePath = '../../public/uploads/' . $imageName . '.' . $imageExtension;

                // Decode the base64 image
                $imageData = explode(',', $image);
                $imageData = base64_decode($imageData[1]);

                // Save the image
                if (!is_dir('../../public/uploads/')) {
                    mkdir('../../public/uploads/', 0777, true);
                }

                $actualImagePath = 'http://localhost/nextgen-parts/public/uploads/' . $imageName . '.' . $imageExtension;

                if (file_put_contents($imagePath, $imageData)) {
                    $uploadedImages[] = $actualImagePath;
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to upload image'
                    ]);
                    exit();
                }
            }

            // Insert product into database
            $query = "INSERT INTO products (name, description, price, images) VALUES (:name, :description, :price, :images)";
            $stmt = Database::iud($query, [
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':images' => json_encode($uploadedImages)
            ]);

            if (!$stmt) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add product',
                    'error' => $stmt->errorInfo()
                ]);
                exit();
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Product added successfully',
            ]);
        }
    }
}