<?php 
    namespace Dezine\Actions;
    use Dezine\Helpers\Cleaner;
    use Dezine\Auth\DB;
    use DateTime;

    class Warning{
        public static function sendWarning($uid, $user_id, $reason){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into warnings (user_id, warning, warning_timestamp, moderator_id) values (:user_id, :warning_reason, :timestamp, :moderator_id);");
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':warning_reason', $reason);
            $statement->bindValue(':timestamp', self::getDateTime());
            $statement->bindValue(':moderator_id', $uid);
            return $statement->execute();
        }

        private static function getDateTime(){
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