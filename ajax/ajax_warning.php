<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use Dezine\Auth\Warning;

    if (!empty($_POST)) {
        $warning_id = $_POST['warning_id'];   
        $warning = new Warning();    
        
        if($warning->removeWarning($warning_id)){
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