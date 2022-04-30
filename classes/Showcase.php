<?php
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    class Showcase{
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

        public function addToShowcase(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into showcase (post_id, user_id) values (:post_id, :user_id)");
            $statement->bindValue(":post_id", $this->getPostId());
            $statement->bindValue(":user_id", $this->getUserId());
            return $statement->execute();
        }

        public function removeFromShowcase(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("delete from showcase where post_id = :post_id and user_id = :user_id");
            $statement->bindValue(":post_id", $this->getPostId());
            $statement->bindValue(":user_id", $this->getUserId());
            return $statement->execute();
        }
    }