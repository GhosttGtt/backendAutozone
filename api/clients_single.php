<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$me = new Clients($db);

$me->id = isset($_GET['id']) ? $_GET['id'] : die();
$me->read_single();

$me_arr = array(
    'id' => $me -> id,
    'name' => $me -> name,
    'lastname' => $me -> lastname,
    'phone' => $me -> phone,
    'email' => $me -> email,
    
    
);

print_r(json_encode($me_arr));