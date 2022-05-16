<?php
    namespace Classes\Actions;
    use \Helpers\Cleaner;
    use Classes\Auth\DB;
    use Exception;
    use PDO;

    class Comment
    {
        private $text;
        private $postId;
        private $userId;        

        public function getText()
        {
            return $this->text;
        }

        public function setText($text)
        {   
            if(empty($text)){
                throw new Exception("Comment cannot be empty");
            }

            $text = Cleaner::cleanInput($text);
            $this->text = $text;

            return $this;
            
        }

        public function getPostId()
        {
            return $this->postId;
        }

        public function setPostId($postId)
        {
            $postId = Cleaner::cleanInput($postId);
            $this->postId = $postId;

            return $this;
        }


        public function getUserId()
        {
            return $this->userId;
        }

        public function setUserId($userId)
        {
            $userId = Cleaner::cleanInput($userId);
            $this->userId = $userId;

            return $this;
        }

        public function save()
        {
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into comments (comment, post_id, user_id) values (:text, :postId, :userId)");
            
            $text = $this->getText();
            $postId = $this->getPostId();
            $userId = $this->getUserId();
            
            $statement->bindValue(':text', $text);
            $statement->bindValue(':postId', $postId);
            $statement->bindValue(':userId', $userId);
            $res = $statement->execute();

            return $res;
        }

        public static function getCommentsByPostId($postId)
        {
            $conn = DB::getInstance();
            $statement = $conn->prepare("SELECT comments.comment, comments.user_id, users.username, users.profile_image FROM comments INNER JOIN users ON users.id = comments.user_id WHERE comments.post_id = :postId");
            $statement->bindValue(':postId', $postId);
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

    }
