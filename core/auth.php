<?php
require_once '../vendor/autoload.php';
include_once('../includes/config.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$key = JWT_SECRET_KEY; // Debe ser la misma clave secreta

function getBearerToken() {
    $headers = apache_request_headers();
    if(isset($headers['Authorization'])){
        if(preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){
            return $matches[1];
        }
    }
    return null;
}

$jwt = getBearerToken();
if(!$jwt){
    http_response_code(401);
    echo json_encode(['message' => 'Token no proporcionado']);
    exit;
}

try {
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    // $decoded contiene los datos del usuario autenticado
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message' => 'Token inválido']);
    exit;
}