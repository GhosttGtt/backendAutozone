<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../core/initialize.php');
include_once('../core/auth.php');

$car = new Cars($db);

$data = json_decode(file_get_contents("php://input"));

$car->id = $data->id;

if($car->delete()){
    echo json_encode(
        array('message' => 'Carro eliminado con éxito')
    );
}else{
    echo json_encode(
        array('message' => 'Error al eliminar el carro')
    );
}