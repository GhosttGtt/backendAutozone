<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');

// Instantiate post
$mess = new Clients($db);


$data = json_decode(file_get_contents("php://input"));

$mess->id = $data->id;

// Soft delete: actualizar el campo 'deleted' a 1 en vez de eliminar físicamente
if ($mess->softDelete()) {
    echo json_encode(
        array('message' => 'Usuario eliminado con éxito')
    );
} else {
    echo json_encode(
        array('message' => 'Error al eliminar el usuario')
    );
}
