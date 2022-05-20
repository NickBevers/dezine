<?php 
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");
    include_once(__DIR__ . "/../classes/Warning.php");

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