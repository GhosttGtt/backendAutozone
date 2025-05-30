<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate API 
include_once('../core/initialize.php');

// Instantiate post
$post = new Post($db);

//blog posts query
$result = $post->read();

//get the row count
$num = $result->rowCount();

if($num > 0){
    $posts_arr = array();
    $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name,
            /* 'created_at' => $created_at */
        );
        // Push to "data"
        array_push($posts_arr['data'], $post_item);
    }

    // convert to JSON and output
    echo json_encode($posts_arr);
}else{

    echo json_encode(array('message' => 'No posts found'));

}