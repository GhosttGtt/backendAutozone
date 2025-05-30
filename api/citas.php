<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/appointment.php');
// Instantiate post
$mess = new Citas($db);

$result = $mess->read();

$num = $result->rowCount();

if($num > 0){
    $messs_arr = array();
    $messs_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $mess_item = array(
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'datetime' => $datetime,
            'personas' => $people,
            
            /* 'created_at' => $created_at */
        );
        // Push to "data"
        array_push($messs_arr['data'], $mess_item);
    }

    // convert to JSON and output
    echo json_encode($messs_arr);
}else{

    echo json_encode(array('message' => 'No posts found'));

}