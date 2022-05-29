<?php 
    require __DIR__ . '/../vendor/autoload.php';
    use \Dezine\Actions\Report;

    if (!empty($_POST)) {
        $report_id = $_POST['report_id'];
        if(intval(Report::checkReport($report_id)) === 0){
            $message = Report::archiveReport($report_id);
            $response = [
                "status" => "archived",
                "message" => "Report has been archived successfully."
            ];
        } else if(intval(Report::checkReport($report_id)) === 1){
            $message = Report::removeArchive($report_id);
            $response = [
                "status" => "unarchived",
                "message" => "Report has been unarchived successfully."
            ];
        } else{
            $response = [
                "status" => "error",
                "message" => "Something has gone wrong, our apologies."
            ];
        }
        echo json_encode($response);
    }