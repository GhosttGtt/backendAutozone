<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Acces-Control-Allow-Headers, Authorization, X-Requested-With');

include_once('../core/initialize.php');
include_once('../core/auth.php');

$car = new Cars($db);

// Validar campos obligatorios (excepto imagen, que va en $_FILES)
if(
    empty($_POST['brand']) ||
    empty($_POST['description']) ||
    empty($_POST['motor']) ||
    empty($_POST['fuel']) ||
    empty($_POST['model']) ||
    empty($_POST['year']) ||
    empty($_POST['price']) ||
    empty($_POST['type_id']) ||
    empty($_POST['stock']) ||
    !isset($_FILES['image']) || $_FILES['image']['error'] != 0
){
    echo json_encode(['message' => 'Todos los campos son obligatorios']);
    exit;
}

// Asignar datos desde $_POST
$car->brand = $_POST['brand'];
$car->description = $_POST['description'];
$car->motor = $_POST['motor'];
$car->fuel = $_POST['fuel'];
$car->model = $_POST['model'];
$car->year = $_POST['year'];
$car->price = $_POST['price'];
$car->type_id = $_POST['type_id'];
$car->stock = $_POST['stock'];

// Manejo de la imagen
$upload_dir = '../img/cars/';
if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0777, true);
}
$filename = uniqid() . '_' . basename($_FILES['image']['name']);
$target_file = $upload_dir . $filename;

if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)){
    $car->image = $filename; 
} else {
    echo json_encode(['message' => 'Error al subir la imagen']);
    exit;
}

if($car->create()){
    echo json_encode(['message' => 'Carro creado correctamente']);
} else {
    echo json_encode(['message' => 'Car not created']);
}