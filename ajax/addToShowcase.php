<?php
    include_once("./../autoloader.php");

    if (!empty($_POST["addToShowcase"])) {
        $postId = $_POST['postId'];
    
        try {
            $like = new Showcase();
            $like->setPostId($postId);
            $like->setUserId($_SESSION["id"]);
            $like->addToShowcase();

            $response = [
                "status" => "success",
                "message" => "Add to showcase was successfull."
            ];
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }

        echo json_encode($response);
    }