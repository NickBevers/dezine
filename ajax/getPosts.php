<?php 
    include_once(__DIR__ . "/../autoloader.php");
    
    $post = new Post();
    $posts = $post->getSomePosts("desc", 0, 40);

    if ($posts) {
        $posts = cleanValues($posts);
        $response = [
            "status" => "success",
            "data" => ["length"=> count($posts), "posts" => $posts],
        ];
    } else{
        $response = [
            "status" => "error",
            "message" => "Something has gone wrong, our apologies."
        ];
    }

    echo json_encode($response);

    function cleanValues($posts){
        for($i= 0; $i<count($posts); $i++){
            $posts[$i]["colors"] = json_decode($posts[$i]["colors"]);
            $posts[$i]["color_group"] = json_decode($posts[$i]["color_group"]);
            $posts[$i]["tags"] = json_decode($posts[$i]["tags"]);
        }
        return $posts;
    }    