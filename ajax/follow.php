<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use \Dezine\Actions\Follow;

    if (!empty($_POST)) {
        $follower_id = $_POST['follower_id'];
        $user_id = $_POST['user_id'];
        $follow = new Follow();
        $follow->setFollower_id($follower_id);
        $follow->setUser_id($user_id);

        if(Follow::isFollowing($follower_id, $user_id)){
            if($follow->unfollowUser()){
                $response = [
                    "status" => "success",
                    "action" => "unfollow",
                    "message" => "You are no longer following this user."
                ];
    
            } else{
                $response = [
                    "status" => "error",
                    "message" => "Something has gone wrong, our apologies."
                ];
            }
        } else{
            if(intval($follow->followUser()) > 0){
                $response = [
                    "status" => "success",
                    "action" => "follow",
                    "message" => "You are now following this user."
                ];
            } else{
                $response = [
                    "status" => "error",
                    "message" => "Something has gone wrong, our apologies."
                ];
            }
        }
        echo json_encode($response);
    }