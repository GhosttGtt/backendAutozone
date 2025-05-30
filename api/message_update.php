<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:PUT');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$mess = new Message($db);


$data = json_decode(file_get_contents("php://input"));

$mess->id = $data->id;
$mess->status = $data->status;


if($mess->update()){
    echo json_encode(
        array('message' => 'Message sent successfully')
    );
}else{
    echo json_encode(
        array('message' => 'Message not sent')
    );
}