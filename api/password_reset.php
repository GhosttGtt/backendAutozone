<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
$user = new Users($db);


if ($_SERVER['CONTENT_TYPE'] === 'application/json' || strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
} else {
    $email = $_POST['email'] ?? '';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Email no válido']);
    exit;
}


$query = 'SELECT * FROM users WHERE email = :email LIMIT 1';
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userData) {

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
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; // Cambia esto por el SMTP de tu dominio Hostinger
        $mail->SMTPAuth = true;
        $mail->Username = 'autozone@alexcg.de'; // Cambia por tu correo real
        $mail->Password = 'Autozone$6894'; // Cambia por tu contraseña real o app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('autozone@alexcg.de', 'AutoZone'); // Cambia por tu correo real
        $mail->addAddress($email);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo json_encode([
            'status_code' => 200,
            'message' => 'Se ha enviado un enlace de recuperación.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['message' => 'No se pudo enviar el correo: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['message' => 'Error, su cuenta no existe.']);
}
