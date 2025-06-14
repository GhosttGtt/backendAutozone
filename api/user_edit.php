<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');
include_once('../core/auth.php');
$user = new Users($db);

// Permitir datos por JSON o por POST
if (
    isset($_SERVER['CONTENT_TYPE']) &&
    (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
) {
    $data = json_decode(file_get_contents('php://input'), true);
    // Si el id no está en $data, intenta buscarlo en $_POST (por si es form-data)
    if (!isset($data['id']) && isset($_POST['id'])) {
        $data['id'] = $_POST['id'];
    }
} else {
    $data = $_POST;
}

$user->id = $data['id'] ?? null;
if (!$user->id) {
    echo json_encode(['message' => 'ID de usuario requerido', 'debug' => [
        'data' => $data,
        'post' => $_POST,
        'files' => $_FILES
    ]]);
    exit;
}

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
    $user->photo = $data['photo'] ?? null;
}

// Asignar datos desde $data, no desde $_POST
$user->name = $data['name'] ?? '';
$user->username = $data['username'] ?? '';
$user->email = $data['email'] ?? '';

if (!empty($data['password'])) {
    $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
} else {
    $user->password = $data['old_password'] ?? '';
}

if ($user->edit()) {
    echo json_encode(['message' => 'Usuario editado correctamente']);
} else {
    echo json_encode(['message' => 'No se pudo editar el usuario']);
}
