<?php
    include_once(__DIR__ . "./../bootstrap.php");
    use \Classes\Actions\Comment;
    
    if (!empty($_POST)) {
        try {
            $comment = new Comment();
            $comment->setPostId($_POST['postId']);
            $comment->setText($_POST['text']);
            $comment->setUserId($_POST['userId']);
            $comment->save();

            $response = [
                'status' => 'success',
                'postId' => $comment->getPostId(),
                'text' => $comment->getText(),
                'userId' => $comment->getUserId(),
                'message' => 'comment saved'
            ];
        } catch (exception $e) {
            $response = [
                'status' => 'failure',
                'message' => $e->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    };
