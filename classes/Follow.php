<?php
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    class Follow{
        private $follower_id;
        private $user_id;
        
        public function getFollower_id(){return $this->follower_id;}

        public function setFollower_id($follower_id){
            $follower_id = Cleaner::cleanInput($follower_id);
            $this->follower_id = $follower_id;
            return $this;
        }

        public function getUser_id(){return $this->user_id;}

        public function setUser_id($user_id){
            $user_id = Cleaner::cleanInput($user_id);
            $this->user_id = $user_id;
            return $this;
        }

        public static function isFollowing($follower_id, $user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from follows where follower_id = :follower_id and user_id = :user_id;");
            $statement->bindValue(':follower_id', $follower_id);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        
        public function followUser(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into follows (follower_id, user_id) values (:follower_id, :user_id);");
            $statement->bindValue(':follower_id', $this->follower_id);
            $statement->bindValue(':user_id', $this->user_id);
            $statement->execute();
            return $statement->fetch();
        }

        public function unfollowUser(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from follows where follower_id = :follower_id and user_id = :user_id;");
            $statement->bindValue(':follower_id', $this->follower_id);
            $statement->bindValue(':user_id', $this->user_id);
            $statement->execute();
            return $statement->fetch();
        }
    }