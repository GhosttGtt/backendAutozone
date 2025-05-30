<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');

$user = new Users($db);

// Obtener el email del POST o JSON
if ($_SERVER['CONTENT_TYPE'] === 'application/json' || strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
} else {
    $email = $_POST['email'] ?? '';
}

// Validar email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Email no válido']);
    exit;
}

// Buscar usuario por email
$query = 'SELECT * FROM users WHERE email = :email LIMIT 1';
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    // Generar un token único
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar el token y expiración en la base de datos
    $update = $db->prepare('UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id');
    $update->bindParam(':token', $token);
    $update->bindParam(':expires', $expires);
    $update->bindParam(':id', $userData['id']);
    $update->execute();

    // Enviar el email de recuperación
    $reset_link = "https://www.alexcg.de/autozone/reset_password.php?token=$token";
    $subject = "Recupera tu contraseña";
    $message = "Hola,\n\nHaz clic en el siguiente enlace para recuperar tu contraseña:\n$reset_link\n\nEste enlace expirará en 1 hora.";
    $headers = "From: no-reply@autozone\r\n";
    mail($email, $subject, $message, $headers);

    echo json_encode(['message' => 'Si el email existe, se ha enviado un enlace de recuperación.']);
} else {
    // Por seguridad, no reveles si el email existe o no
    echo json_encode(['message' => 'Si el email existe, se ha enviado un enlace de recuperación.']);
}
