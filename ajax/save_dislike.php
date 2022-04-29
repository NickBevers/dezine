<?php
    include_once("./../autoloader.php");

    session_start();

    if (!empty($_POST["unliked"])) {
        $postId = $_POST['postId'];
        // var_dump($postId);

        try {
            $like = new Like();
            $like->setPostId($postId);
            $like->setUserId($_SESSION["id"]);
            $like->addDislike();
            $likes = Like::getLikes($postId);

            $response = [
                "status" => "success",
                "message" => "Dislike was successfull.",
                "data" => $likes
            ];
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }

        echo json_encode($response);
    }