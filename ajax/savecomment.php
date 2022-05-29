<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Dezine\Actions\Comment;
    use Dezine\Helpers\Cleaner;

    if (!empty($_POST)) {
        try {
            $comment = new Comment();
            $comment->setPostId($_POST['postId']);
            $comment->setText($_POST['text']);
            $comment->setUserId($_POST['userId']);
            if($comment->save()){
                $response = [
                    'status' => 'success',
                    'postId' => Cleaner::xss($comment->getPostId()),
                    'text' => Cleaner::xss($comment->getText()),
                    'userId' => Cleaner::xss($comment->getUserId()),
                    'message' => 'comment saved'
                ];
            } else{
                $response = [
                    'status' => 'error',
                    'message' => "Something went wrong"
                ];
            }            
        } catch (exception $e) {
            $response = [
                'status' => 'failure',
                'message' => $e->getMessage()
            ];
        }
        echo json_encode($response);
    };
