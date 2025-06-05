<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$sale = new Sales($db);

//blog posts query
$result = $sale->read();

//get the row count
$num = $result->rowCount();

if ($num > 0) {
    $sales_arr = array();
    $sales_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $sale_item = array(
            'id' => $id,
            'client_id' => $client_id,
            'client_name' => $client_name,
            'client_lastname' => $client_lastname,
            'cars_id' => $cars_id,
            'cars_name' => $cars_name,
            'cars_model' => $cars_model,
            'cars_year' => $cars_year,
            'cars_motor' => $cars_motor,
            'cars_fuel' => $cars_fuel,
            'cars_price' => $cars_price,
            'cars_image' => $cars_image,
            'cars_type' => $cars_type,
            'total' => $total,
            'payment_id' => $payment_id,
            'payment' => $payment,
            'date_sale' => $dateSales,
            'status_id' => $status_id,

        );
        // Push to "data"
        array_push($sales_arr['data'], $sale_item);
    }

    // convert to JSON and output
    echo json_encode($sales_arr);
} else {

    echo json_encode(array('message' => 'No posts found'));
}
