<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$post = new Sales($db);

$post->id = isset($_GET['id']) ? $_GET['id'] : die();
$post->read_single();

$post_arr = array(
    'id' => $post->id,
    'client_id' => $post->client_id,
    'client_name' => $post->client_name,
    'client_lastname' => $post->client_lastname,
    'cars_id' => $post->cars_id,
    'cars_name' => $post->cars_name,
    'cars_price' => $post->cars_price,
    'cars_image' => $post->cars_image,
    'cars_motor' => $post->cars_motor,
    'cars_year' => $post->cars_year,
    'cars_model' => $post->cars_model,
    'cars_type' => $post->cars_type,
    'total' => $post->total,
);

print_r(json_encode($post_arr));