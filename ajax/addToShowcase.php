<?php
    include_once("./../bootstrap.php");
    use \Classes\Content\Showcase;

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
    
        try {
            $showcase = new \Classes\Content\Showcase();
            $showcase->setPostId($postId);
            var_dump($showcase->setPostId($postId));
            $showcase->setUserId($_SESSION["id"]);
            var_dump("does work");
            if(Showcase::checkShowcase($postId, $_SESSION["id"])){
                var_dump($showcase);
                var_dump("does work 1");
                $showcase->removeFromShowcase();
                var_dump($showcase->removeFromShowcase());
                var_dump("does work 2");
                $response = [
                    "status" => "success",
                    "message" => "Remove from showcase was successfull."
                ];
            }else{
                var_dump($showcase);
                var_dump("does work 3");
                $showcase->addToShowcase();
                var_dump($showcase->addToShowcase());
                var_dump("does work 4");
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