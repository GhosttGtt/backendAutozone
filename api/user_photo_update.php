<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');
include_once('../core/auth.php');

$user = new Users($db);

// Solo aceptar peticiones POST con form-data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

// Validar ID de usuario (aceptar también por JSON)
if (isset($_POST['id'])) {
    $user->id = $_POST['id'];
} else if (isset($_GET['id'])) {
    $user->id = $_GET['id'];
} else if (
    isset($_SERVER['CONTENT_TYPE']) &&
    (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
) {
    $json = json_decode(file_get_contents('php://input'), true);
    $user->id = $json['id'] ?? null;
} else {
    $user->id = null;
}
if (!$user->id) {
    echo json_encode(['message' => 'ID de usuario requerido', 'debug' => [
        'post' => $_POST,
        'get' => $_GET,
        'files' => $_FILES
    ]]);
    exit;
}

// Validar y procesar la imagen
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $upload_dir = '../img/users/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        $user->photo = $filename;
    } else {
        echo json_encode(['message' => 'Error al subir la imagen']);
        exit;
    }
} else {
    echo json_encode(['message' => 'No se recibió la imagen']);
    exit;
}

// Actualizar solo la foto
$query = 'UPDATE users SET photo = :photo WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':photo', $user->photo);
$stmt->bindParam(':id', $user->id);
if ($stmt->execute()) {
    echo json_encode(['message' => 'Fotografía actualizada correctamente', 'photo' => $user->photo]);
} else {
    echo json_encode(['message' => 'No se pudo actualizar la fotografía']);
}
