<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Incluir inicialización y clase Login
require_once('../vendor/autoload.php');
include_once('../core/initialize.php');
include_once('../core/login_client.php');

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $login = new Login($db);
    if ($login->login($data->email, $data->password)) {
        $payload = [
            "id" => $login->id,
            "email" => $login->email,
            "name" => $login->name,
            "lastname" => $login->lastname,
            "phone" => $login->phone,
            "iat" => time(),
            "exp" => time() + 3600 // 1 hora de validez
        ];
        echo json_encode([
            'success' => true,
            'user' => $payload
        ]);
        return;
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ]);
        return;
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos'
    ]);
    return;
}