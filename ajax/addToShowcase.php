<?php
    include_once("./../bootstrap.php");
    use Dezine\Content\Showcase;

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
        $userId = $_POST["userId"];
    
        try {
            $showcase = new Showcase();
            $showcase->setPostId($postId);
            $showcase->setUserId($userId);
            if(Showcase::checkShowcase($postId, $userId)){
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