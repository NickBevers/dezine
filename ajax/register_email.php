<?php 
    include_once("./../autoloader.php");

    if (!empty($_POST)) {
        $email = $_POST['email'];
        $user = new User();
        $user->setEmail($email);
        $user->userExists();

        if(!$user->userExists()){
            $response = [
                "status" => "success",
                "message" => "this email is available."
            ];
            echo json_encode($response);
        } else{
            $response = [
                "status" => "error",
                "message" => "this email is already in use."
            ];
            echo json_encode($response);
        }
        
    }