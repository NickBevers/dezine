<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Dezine\Actions\Comment;
    
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
