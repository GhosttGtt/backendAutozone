<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../core/initialize.php');

$user = new Users($db);

$data = json_decode(file_get_contents("php://input"));



// Verifica si se recibió un archivo
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
    $user->photo = null; // O un valor por defecto
}

// Recibe otros datos (ya validados)
$user->name = $data->name;
$user->username = $data->username;
$user->email = $data->email;
// Asignar la foto (si se subió)
$user->password = password_hash($data->password, PASSWORD_BCRYPT); // Encriptar la contraseña
$user->role = $data->role;



if ($user->create()) {
    echo json_encode(array('message' => 'Usuario creado correctamente'));
} else {
    echo json_encode(array('message' => 'No se pudo crear el usuario'));
}
