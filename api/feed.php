<?php 
    use \Dezine\Content\Post;
    use \Dezine\Helpers\Cleaner;

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json;charset=utf-8');
    $posts = Post::getSomePosts("desc", 0, 40);
    echo json_encode(Cleaner::xss($posts));
?>