<?php
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");
    
    if(!empty($_POST)) {
        $comment = new Comment();
        $comment->setPostId($_POST['postId']);
        $comment->setText($_POST['text']);
        $comment->setUserId($_SESSION['id']);

        $comment->save();

        $response = [
            'status' => 'succes',
            'commment' => Cleaner::cleanInput($comment->getText()),
            'message' => 'comment saved'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    };


?>