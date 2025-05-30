<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');

// Instantiate post
$car = new Cars($db);

//blog posts query
$result = $car->read();

//get the row count
$num = $result->rowCount();
$url = 'https://www.alexcg.de/autozone/img/cars/';
if($num > 0){
    $cars_arr = array();
    $cars_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $car_item = array(
            'id' => $id,
            'brand' => $brand,
            'description' => $description,
            'model' => $model,
            'year' => $year,
            'motor' => $motor,
            'price' => $price,
            'image' => $url . $image,
            'type_id' => $type_id,
            'type_name' => $type_name,
            'stock' => $stock,
            /* 'created_at' => $created_at */
        );
        // Push to "data"
        array_push($cars_arr['data'], $car_item);
    }

    // convert to JSON and output
    echo json_encode($cars_arr);
}else{

    echo json_encode(array('message' => 'No posts found'));

}