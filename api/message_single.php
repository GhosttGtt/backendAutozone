<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$me = new Message($db);

$me->id = isset($_GET['id']) ? $_GET['id'] : die();
$me->read_single();

$me_arr = array(
    'id' => $me -> id,
    'name' => $me -> name,
    'phone' => $me -> phone,
    'email' => $me -> email,
    'subject' => $me -> subject,
    'message' => html_entity_decode($me -> message),
    'status' => $me -> status,
    
);

print_r(json_encode($me_arr));