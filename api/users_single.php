<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$me = new Users($db);

$me->id = isset($_GET['id']) ? $_GET['id'] : die();
$me->read_single();

if (empty($me->name)) {
    echo json_encode(['message' => 'Usuario no encontrado']);
    exit;
}


$me_arr = array(
    'id' => $me->id,
    'name' => $me->name,
    'username' => $me->username,
    'email' => $me->email,
    'photo' => $me->photo,
    'role' => $me->role,
    'created_at' => $me->created_at,
);

print_r(json_encode($me_arr));
