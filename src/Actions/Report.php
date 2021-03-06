<?php
    namespace Dezine\Actions;
    use \Dezine\Auth\DB;
    use \Dezine\Helpers\Cleaner;
    use DateTime;

    class Report {
        private $reported_user_id;
        private $post_id;
        private $reason;
        
        public function getPostid(){
            return $this->post_id;
        }

        public function setPostid($post_id){
            $post_id = Cleaner::cleanInput($post_id);
            $this->post_id = $post_id;
            return $this;
        }

        public function getReportedUserId(){
            return $this->reported_user_id;
        }
        
        public function setReportedUserId($reported_user_id){
            $reported_user_id = Cleaner::cleanInput($reported_user_id);
            $this->reported_user_id = $reported_user_id;
            return $this;
        }

        public function getReason(){
            return $this->reason;
        }
        
        public function setReason($reason){
            $reason = Cleaner::cleanInput($reason);
            $this->reason = $reason;
            return $this;
        }

        public function sendReport($user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into reports (post_id, reported_user_id, user_id, reason, timestamp) values (:post_id, :reported_user_id, :user_id, :reason, :timestamp);");
            $reason = $this->getReason();
            $statement->bindValue(':post_id', $this->post_id);
            $statement->bindValue(':reported_user_id', $this->reported_user_id);
            $statement->bindValue(':user_id', Cleaner::cleanInput($user_id));
            $statement->bindValue(':reason', Cleaner::cleanInput($reason));
            $statement->bindValue(':timestamp', $this->getDateTime());
            $res = $statement->execute();
            return $res;
        }

        private function getDateTime(){
            $dateTime = new DateTime();
            $dateTime = $dateTime->format('Y-m-d H:i:s');
            return $dateTime;
        }

        public static function getReportedPostbyId($post_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($post_id));
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

        public static function getReportedUserbyId($reported_user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($reported_user_id));
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

        public static function getReports(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from reports order by timestamp desc");
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function archiveReport($report_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update reports set archived = 1 where id = :report_id");
            $statement->bindValue(':report_id', Cleaner::cleanInput($report_id));
            $statement->execute();
            $message = "Report has been archived";
            return $message;
        }

        public static function checkReport($report_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select archived from reports where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($report_id));
            $statement->execute();
            $result = $statement->fetch();
            return $result["archived"];
        }

        public static function removeArchive($report_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update reports set archived = 0 where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($report_id));
            $statement->execute();
            $message = "The report has been unarchived";
            return $message;
        }
    }