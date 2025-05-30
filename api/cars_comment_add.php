<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

// Include the necessary files
include_once('../core/initialize.php');
include_once('../core/comment.php');

$comment = new Comment($db);
// Get the data from the request
$data = json_decode(file_get_contents("php://input"));
// Check if the required fields are presents
$comment->car_id = $data->car_id;
$comment->stars = $data->stars; 
$comment->comment = $data->comment;

if($comment->create()){
    echo json_encode(
        array('message' => 'Comentario enviado con éxito')
    );
}else{
    echo json_encode(
        array('message' => 'Error al enviar el comentario')
    );
}