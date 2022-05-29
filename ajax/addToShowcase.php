<?php
    require __DIR__ . '/../vendor/autoload.php';
    use \Dezine\Content\Showcase;

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
        $userId = $_POST["userId"];
    
        try {
            $showcase = new Showcase();
            $showcase->setPostId($postId);
            $showcase->setUserId($userId);
            if(Showcase::checkShowcase($postId, $userId)){
                if($showcase->removeFromShowcase()){
                   $response = [
                        "status" => "success",
                        "message" => "Remove from showcase was successfull."
                    ]; 
                } else{
                    $response = [
                        "status" => "error",
                        "message" => "Something went wrong."
                    ]; 
                }                
            }else{
                if($showcase->addToShowcase()){
                     $response = [
                        "status" => "success",
                        "message" => "Add to showcase was successfull."
                    ];
                }else{
                    $response = [
                        "status" => "error",
                        "message" => "Something went wrong."
                    ];
                }               
            }
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }
        echo json_encode($response);
    }