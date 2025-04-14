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

if (!$data || $data == null || empty($data)) {
    echo    json_encode([
        'status' => 'error',
        'message' => 'No data provided'
    ]);
    exit();
}

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

    echo json_encode([
        'status' => 'success',
        'message' => 'Product added successfully',
        'data' => [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'images' => $uploadedImages
        ]
    ]);
}
