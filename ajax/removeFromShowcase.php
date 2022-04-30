<?php
    include_once("./../autoloader.php");

    if (!empty($_POST["removeFromShowcase"])) {
        $postId = $_POST['postId'];
        // var_dump($postId);

        try {
            $like = new Showcase();
            $like->setPostId($postId);
            $like->setUserId($_SESSION["id"]);
            $like->removeFromShowcase();

            $response = [
                "status" => "success",
                "message" => "Remove from showcase was successfull."
            ];
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }

        echo json_encode($response);
    }