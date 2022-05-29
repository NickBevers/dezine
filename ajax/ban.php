<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use \Dezine\Auth\User;
    use \Dezine\Helpers\Cleaner;

    if (!empty($_POST)) {
        $id = $_POST['id'];
        if(intval(User::checkBan($id)) === 0){
            $message = User::addBan($id);
        }elseif(intval(User::checkBan($id)) === 1){
            $message = User::removeBan($id);
        }
        
        if(isset($message)){
            $response = [
                "status" => "success",
                "message" => Cleaner::xss($message)
            ];
        } else{
            $response = [
                "status" => "error",
                "message" => "Something has gone wrong, our apologies."
            ];
        }        
        echo json_encode($response);
    }