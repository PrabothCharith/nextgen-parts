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

    echo json_encode([
        'status' => 'success',
        'message' => 'Product added successfully',
        'data' => [
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]
    ]);
}
