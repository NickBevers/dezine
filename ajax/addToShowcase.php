<?php
    include_once("./../autoloader.php");

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
    
        try {
            $showcase = new Showcase();
            $showcase->setPostId($postId);
            $showcase->setUserId($_SESSION["id"]);
            if(Showcase::checkShowcase($postId, $_SESSION["id"])){
                $showcase->removeFromShowcase();
                $response = [
                    "status" => "success",
                    "message" => "Remove from showcase was successfull."
                ];
            }else{
                $showcase->addToShowcase();
                $response = [
                    "status" => "success",
                    "message" => "Add to showcase was successfull."
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