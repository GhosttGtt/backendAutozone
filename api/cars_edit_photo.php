<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once('../core/initialize.php');

$car = new Cars($db);


if (isset($_POST['id'])) {
    $car->id = $_POST['id'];
} else if (isset($_GET['id'])) {
    $car->id = $_GET['id'];
} else {
    $car->id = null;
}

if (!$car->id) {
    echo json_encode(['message' => 'ID de auto requerido', 'debug' => [
        'post' => $_POST,
        'get' => $_GET,
        'files' => $_FILES
    ]]);
    exit;
}

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
} else {
    echo json_encode(['message' => 'No se recibió la imagen']);
    exit;
}

$query = 'UPDATE cars SET image = :image WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':image', $car->image);
$stmt->bindParam(':id', $car->id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Foto del auto actualizada correctamente', 'image' => $car->image]);
} else {
    echo json_encode(['message' => 'No se pudo actualizar la foto']);
}
