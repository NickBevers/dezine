<?php 
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json;charset=utf-8');
    include_once("./../classes/Post.php");
    $posts = Post::getSomePosts("desc", 0, 40);
    echo json_encode($posts);
?>