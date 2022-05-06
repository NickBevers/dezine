<?php 
    include_once(__DIR__ . "/../autoloader.php");


    if (!empty($_POST)) {
        $reason = $_POST['reason'];
        $report->setPostid($post_id);
        $report->setReportedUserId($reported_user_id);
        $report->setReason($reason);

      


            if($report->sendReport($user_id)){

                    $response = [ 

                    "status" => "success",
                    "message" => "You are no longer following this user."
                ];
                echo json_encode($response);
                //header("Location: home.php");
              } 
              
              
              else{
                $response = [
                    "status" => "error",
                    "message" => "Something has gone wrong, our apologies."
                ];
                echo json_encode($response);
            }
           

    }



    