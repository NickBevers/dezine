<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use \Dezine\Helpers\Cleaner;
    use \Dezine\Actions\Report;

    if (!empty($_POST)) {
        $user_id = $_POST['user_id'];
        $reason = $_POST['reason'];
        $post_id = $_POST['post_id'];
        $reported_user_id = $_POST['reported_user_id'];
       
        $report = new Report();
        $report->setPostid($post_id);
        $report->setReportedUserId($reported_user_id);
        $report->setReason($reason);
       
        if($report->sendReport($user_id)){
            $response = [ 
                "status" => "success",
                "body" => Cleaner::xss($report->getReason()),
                "message" => "You have succesfully reported this post or user."
            ];
        } else{
            $response = [
                "status" => "error",
                "message" => "Something has gone wrong, our apologies."
            ];
        }    
        echo json_encode($response);    
    }



    