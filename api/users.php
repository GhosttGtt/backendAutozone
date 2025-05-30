<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');
include_once('../core/auth.php');
// Instantiate post
$mess = new Users($db);

//blog posts query
$result = $mess->read();

//get the row count
$num = $result->rowCount();

$url = "https://www.alexcg.de/autozone/img/users/";

if ($num > 0) {
    $messs_arr = array();
    $messs_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $mess_item = array(
            'id' => $id,
            'name' => $name,
            'username' => $username,
            'photo' => $url . $photo,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        );
        // Push to "data"
        array_push($messs_arr['data'], $mess_item);
    }

    // convert to JSON and output
    echo json_encode($messs_arr);
} else {

    echo json_encode(array('message' => 'No posts found'));
}
