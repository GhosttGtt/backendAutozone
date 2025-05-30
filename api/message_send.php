<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Methods, Authorization, X-Requested-With');

include_once('../core/initialize.php');

$mess = new Message($db);

$data = json_decode(file_get_contents("php://input"));

$mess->name = $data->name;
$mess->phone = $data->phone;
$mess->email = $data->email;
$mess->subject = $data->subject;
$mess->message = $data->message;
$mess->status = $data->status;


if ($mess->create()) {
    echo json_encode(
        array('message' => 'Message sent successfully')
    );
} else {
    echo json_encode(
        array('message' => 'Message not sent')
    );
}
