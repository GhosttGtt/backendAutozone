<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/appointment.php');
// Instantiate post
$mess = new Citas($db);


$data = json_decode(file_get_contents("php://input"));
// Validate required fields
if(
    empty($data->name) ||
    empty($data->email) ||
    empty($data->datetime) ||
    empty($data->people)
){
    echo json_encode(['message' => 'Complete todos los campos']);
    exit;
}

$mess->name = $data->name;
$mess->email = $data->email;
$mess->datetime = $data->datetime;
$mess->personas = $data->people;


if($mess->create()){
    echo json_encode(
        array('message' => 'Cita creada correctamente')
    );
}else{
    echo json_encode(
        array('message' => 'Message not sent')
    );
}