<?php 
    include_once(__DIR__ . "/../autoloader.php");

    if (!empty($_POST)) {
        $follower_id = $_POST['follower_id'];
        $user_id = $_POST['user_id'];
        $follow = new Follow();
        $follow->setFollower_id($follower_id);
        $follow->setUser_id($user_id);
        
        if($follow->followUser()){
            $response = [
                "status" => "success",
                "message" => "You are now following this user."
            ];
            echo json_encode($response);

        } else{
            $response = [
                "status" => "error",
                "message" => "Something has gone wrong, our apologies."
            ];
            echo json_encode($response);
        }
        
    }