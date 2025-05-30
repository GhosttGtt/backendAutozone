<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../core/initialize.php');// Ajusta la ruta si es necesario


$user = new Clients($db);


if(
    empty($input['name']) ||
    empty($input['lastname']) ||
    empty($input['email']) ||
    empty($input['phone'])||
    empty($input['password'])
){
    echo json_encode(['message' => 'Todos los campos son obligatorios']);
    exit;
}

$user->name = $input['name'];
$user->lastname = $input['lastname'];
$user->email = $input['email'];
$user->phone = $input['phone'];
$user->password = password_hash($input['password'], PASSWORD_DEFAULT); // Hashea la contraseña

if($user->create()){
    echo json_encode(['message' => 'Usuario creado correctamente']);
} else {
    echo json_encode(['message' => 'No se pudo crear el usuario']);
}