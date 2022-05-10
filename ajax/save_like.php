<?php
    include_once("./../autoloader.php");

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
    
        try {
            $like = new Like();
            $like->setPostId($postId);
            $like->setUserId($_SESSION["id"]);
            if(Like::checkLikes($postId, $_SESSION["id"]) === 0){
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