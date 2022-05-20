<?php 
    namespace Dezine\Auth;
    use Dezine\Helpers\Cleaner;
    use Classes\Auth\DB;
    use DateTime;

    class Warning{
        private $username;
        private $warning_reason;

        public function getReasonWarning(){return $this->warning_reason;}
        
        public function setReasonWarning($warning_reason){
            $warning_reason = Cleaner::cleanInput($warning_reason);
            $this->warning_reason = $warning_reason;
            return $this;
        }

        public function sendWarning($uid, $user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into warnings (user_id, moderator_id, warning, warning_timestamp) values (:user_id, :moderator_id, :warning_reason, :timestamp);");
            $warning_reason = $this->getReasonWarning();
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':moderator_id', $uid);
            $statement->bindValue(':warning_reason', $warning_reason);
            $statement->bindValue(':timestamp', $this->getDateTime());
            $res = $statement->execute();
            return $res;
        }

        private function getDateTime(){
            $dateTime = new DateTime();
            $dateTime = $dateTime->format('Y-m-d H:i:s');
            return $dateTime;
        }

        public function removeWarning($warning_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from warnings where id = :warning_id");
            $statement->bindValue(":warning_id", $warning_id);            
            return $statement->execute();
        }
        
    }