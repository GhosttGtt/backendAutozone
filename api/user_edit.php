<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once('../core/initialize.php');
include_once('../core/auth.php');
$user = new Users($db);

$user->id = $_POST['id'] ?? null;
if(!$user->id){
    echo json_encode(['message' => 'ID de usuario requerido']);
    exit;
}

if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
    $upload_dir = '../img/users/';
    if(!is_dir($upload_dir)){
        mkdir($upload_dir, 0777, true);
    }
    $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
    $target_file = $upload_dir . $filename;

    if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)){
        $user->photo = $filename; 
    } else {
        echo json_encode(['message' => 'Error al subir la imagen']);
        exit;
    }
} else {
    
    $user->photo = $_POST['photo'] ?? null;
}

$user->name = $_POST['name'] ?? '';
$user->username = $_POST['username'] ?? '';
$user->email = $_POST['email'] ?? '';

if(!empty($_POST['password'])){
    $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {

    $user->password = $_POST['old_password'] ?? '';
}

if($user->edit()){
    echo json_encode(['message' => 'Usuario editado correctamente']);
} else {
    echo json_encode(['message' => 'No se pudo editar el usuario']);
}