<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');
include_once('../core/auth.php');

$client = new Clients($db);

// Leer datos JSON o POST
if ($_SERVER['CONTENT_TYPE'] === 'application/json' || strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    $data = $_POST;
}

$client->id = $data['id'] ?? null;
if (!$client->id) {
    echo json_encode(['success' => false, 'message' => 'ID de cliente requerido']);
    exit;
}

$client->name = $data['name'] ?? '';
$client->lastname = $data['lastname'] ?? '';
$client->phone = $data['phone'] ?? '';
$client->email = $data['email'] ?? '';

// Si se envía una nueva contraseña, actualizarla (opcional)
if (!empty($data['password'])) {
    $client->password = password_hash($data['password'], PASSWORD_DEFAULT);
} else {
    $client->password = null; // No actualizar si no se envía
}

if ($client->edit()) {
    echo json_encode(['success' => true, 'message' => 'Cliente editado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo editar el cliente']);
}
