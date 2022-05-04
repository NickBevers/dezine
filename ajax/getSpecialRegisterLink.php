<?php
    include_once("./../autoloader.php");
    
    if(!empty($_POST)){
        try {
            // $specialLink = 
            $link = New Link();
            $link->setUser($_SESSION["id"]);
            $specialLink = $link->generateLink();
            $code = $specialLink['link_code'];
    
            $response = [
                "status" => "success",
                "message" => "Special link generated.",
                "link" => $code
            ];
        } catch (Throwable $t) {
            $response = [
                "status" => "error",
                "message" => "Something went wrong. $t"
            ];
        }
    
        echo json_encode($response);
    }