<?php
    namespace Classes\Actions;
    // include_once(__DIR__ . "/../bootstrap.php");
    // include_once(__DIR__ . "/../helpers/Cleaner.help.php");
    use Cleaner;
    use Classes\Auth\DB;
    
    class Like {
        private $postId;
        private $userId;

        public function getPostId(){return $this->postId;}

        public function setPostId($postId)
        {
            $postId = Cleaner::cleanInput($postId);
            $this->postId = $postId;
            return $this;
        }

        public function getUserId(){return $this->userId;}

        public function setUserId($userId)
        {
            $userId = Cleaner::cleanInput($userId);
            $this->userId = $userId;
            return $this;
        }

        public function addLike(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into likes (post_id, user_id) values (:post_id, :user_id)");
            $statement->bindValue(":post_id", $this->getPostId());
            $statement->bindValue(":user_id", $this->getUserId());
            return $statement->execute();
        }

        public function addDislike(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from likes where post_id = :post_id and user_id = :user_id");
            $statement->bindValue(":post_id", $this->getPostId());
            $statement->bindValue(":user_id", $this->getUserId());
            return $statement->execute();
        }

        public static function getLikes($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from likes where post_id = :post_id");
            $statement->bindValue(":post_id", $postId);
            $statement->execute();
        //     $res = $statement->rowCount();
        //     var_dump($res);
        //     var_dump($postId);
            return $statement->rowCount();
        }

        public static function getLikesbyPostandUser($postId, $userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from likes where post_id = :post_id and user_id = :user_id");
            $statement->bindValue(":post_id", $postId);
            $statement->bindValue(":user_id", $userId);
            $statement->execute();
            $res = $statement->fetch();
            // var_dump($res);
            return $res;
        }

        public static function checkLikes($postId, $userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from likes where post_id = :post_id and user_id = :user_id");
            $statement->bindValue(":post_id", $postId);
            $statement->bindValue(":user_id", $userId);
            $statement->execute();
            $res = $statement->rowCount();
            // var_dump($res);
            return $res;
        }
    }