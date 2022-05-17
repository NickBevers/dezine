<?php
    include_once(__DIR__ . "./../bootstrap.php");
    use \Classes\Actions\Like;

    if (!empty($_POST)) {
        // var_dump($_POST);
        $postId = $_POST['postId'];
        $userId = $_POST["userId"];
    
        try {
            $like = new Like();
            $like->setPostId($postId);
            $like->setUserId($userId);
            if(Like::checkLikes($postId, $userId) === 0){
                $like->addLike();
                $likes = Like::getLikes($postId);

                $response = [
                    "status" => "success",
                    "message" => "Like was successfull.",
                    "data" => $likes
                ];
            }
            else{
                $like->addDislike();
                $likes = Like::getLikes($postId);

                $response = [
                    "status" => "success",
                    "message" => "Dislike was successfull.",
                    "data" => $likes
                ];
            }
            
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }

        echo json_encode($response);
    }