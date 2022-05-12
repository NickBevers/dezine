<?php
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    class Link{
        private $user;

        public function getUser(){
            return $this->user;
        }

        public function setUser($user){
            $user = Cleaner::cleanInput($user);
            $this->user = $user;
            return $this;
        }

        private function randomString($length = 64){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $string =  "";
            for($i = 0; $i < intval($length); $i++){
                $string .= $chars[rand(0, strlen($chars)-1)];
            }
            return $string;
        }
        
        public function generateLink(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into links (user_id, link_code, generated_at) values (:uid, :code, :time)");
            $statement->bindValue(':uid', intval($this->user));
            $statement->bindValue(':code', strval($this->randomString(64)));
            $statement->bindValue(':time', strval($this->getDateTime()));
            $statement->execute();
            $linkId = $conn->lastInsertId();

            $stmt = $conn->prepare("select * from links where id = :id limit 1");
            $stmt->bindValue(':id', $linkId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function removeLink($token){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from links where link_code = :link");
            $statement->bindValue(':link', $token);
            $statement->execute();
        }

        public static function checkLink($token){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from links where link_code = :link");
            $statement->bindValue(':link', $token);
            $statement->execute();
            return $statement->fetch();
        }

        private function getDateTime(){
            $dateTime = new DateTime();
            $dateTime = $dateTime->format('Y-m-d H:i:s');
            return $dateTime;
        }
    }