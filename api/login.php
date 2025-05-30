
<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Incluir inicialización y clase Login
require_once('../vendor/autoload.php');
include_once('../core/initialize.php');
include_once('../core/login.php');
include_once('../includes/config.php');

use \Firebase\JWT\JWT;

$key = JWT_SECRET_KEY;
$data = json_decode(file_get_contents("php://input"));

$url = "https://www.alexcg.de/autozone/img/users/";

if (!empty($data->username) && !empty($data->password)) {
    $login = new Login($db);
    if ($login->login($data->username, $data->password)) {
        // Login correcto, puedes devolver los datos del usuario (sin la contraseña)
        $payload = [
            "id" => $login->id,
            "username" => $login->username,
            "iat" => time(),
            "exp" => time() + 3600 // 1 hora de validez
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $login->id,
                'username' => $login->username,
                'name' => $login->name,
                'email' => $login->email,
                'img' => $url . $login->photo,
                'role' => $login->role
            ],
            'token' => $jwt
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos'
    ]);
}
