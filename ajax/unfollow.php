<?php 
    include_once("./../bootstrap.php");
    use \Classes\Actions\Follow;

    if (!empty($_POST)) {
        $follower_id = $_POST['follower_id'];
        $user_id = $_POST['user_id'];
        $follow = new Follow();
        $follow->setFollower_id($follower_id);
        $follow->setUser_id($user_id);
        
        if($follow->unfollowUser()){
            $response = [
                "status" => "success",
                "message" => "You are no longer following this user."
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