<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use Dezine\Auth\User;

    if (!empty($_POST)) {
        $username = $_POST['username'];
        $user = new User();
        $user->setUsername($username);
        $user->usernameExists();

        if(!$user->usernameExists()){
            $response = [
                "status" => "success",
                "message" => "this username is available."
            ];
            echo json_encode($response);
        } else{
            $response = [
                "status" => "error",
                "message" => "this username is already in use."
            ];
            echo json_encode($response);
        }
    }