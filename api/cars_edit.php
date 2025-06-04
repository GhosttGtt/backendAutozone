<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
include_once('../core/auth.php');

include_once('../core/initialize.php');

$car = new Cars($db);

if (
    isset($_SERVER['CONTENT_TYPE']) &&
    (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}

$car->id = $data['id'] ?? null;
if (!$car->id) {
    echo json_encode(['message' => 'ID de auto requerido']);
    exit;
}

// Solo actualizar los campos que estén presentes y no vacíos
$car->brand = $data['brand'] ?? null;
$car->description = $data['description'] ?? null;
$car->model = $data['model'] ?? null;
$car->year = $data['year'] ?? null;
$car->motor = $data['motor'] ?? null;
$car->price = $data['price'] ?? null;
$car->type_id = $data['type_id'] ?? null;
$car->stock = $data['stock'] ?? null;

// Si se recibe imagen, procesarla
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $upload_dir = '../img/cars/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $car->image = $filename;
    } else {
        echo json_encode(['message' => 'Error al subir la imagen']);
        exit;
    }
} else if (isset($data['image'])) {
    $car->image = $data['image'];
}

// Método para editar el auto (debe existir en la clase Cars)
if ($car->edit()) {
    echo json_encode(['message' => 'Auto actualizado correctamente']);
} else {
    echo json_encode(['message' => 'No se pudo actualizar el auto']);
}
