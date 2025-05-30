<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');

// Instantiate post
$me = new Cars($db);

$me->id = isset($_GET['id']) ? $_GET['id'] : die();
$me->read_single();
$url = 'https://www.alexcg.de/autozone/img/cars/';
$me_arr = array(
    'id' => $me->id,
    'brand' => $me->brand,
    'description' => $me->description,
    'model' => $me->model,
    'year' => $me->year,
    'motor' => $me->motor,
    'price' => $me->price,
    'image' => $url.$me->image,
    'type_id' => $me->type_id,
    'type_name' => $me->type_name,
    'stock' => $me->stock,

);

print_r(json_encode($me_arr));