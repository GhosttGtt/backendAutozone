<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once('../core/initialize.php');
/* include_once('../core/auth.php'); */
include_once('../core/comment.php');

$comment = new Comment($db);

$result = $comment->read();

$num = $result->rowCount();
if($num > 0){
    $comments_arr = array();
    $comments_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $comment_item = array(
            'id' => $id,
            'comment' => $comment,
            'stars' => $stars,
            'car_id' => $car_id,
            'car_brand' => $car_brand,
            'car_model' => $car_model,
            'created_at' => $created_at,
        );
        // Push to "data"
        array_push($comments_arr['data'], $comment_item);
    }
    echo json_encode($comments_arr);
}else{
    echo json_encode(array('message' => 'No comments found'));
}