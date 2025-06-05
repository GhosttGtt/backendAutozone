<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');

// Leer datos JSON
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'] ?? '';
$password = $data['password'] ?? '';

if (empty($token) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Token y contraseña son obligatorios.']);
    exit;
}

// Buscar usuario por token y verificar expiración
$query = 'SELECT * FROM clients WHERE reset_token = :token AND reset_expires > NOW() LIMIT 1';
$stmt = $db->prepare($query);
$stmt->bindParam(':token', $token);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Token inválido o expirado.']);
    exit;
}

// Actualizar la contraseña y limpiar el token
$new_password = password_hash($password, PASSWORD_DEFAULT);
$update = $db->prepare('UPDATE clients SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id');
$update->bindParam(':password', $new_password);
$update->bindParam(':id', $user['id']);
if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    exit;
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la contraseña.']);
}
