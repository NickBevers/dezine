<?php 
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    require 'vendor/autoload.php';
    use League\ColorExtractor\Color;
    use League\ColorExtractor\ColorExtractor;
    use League\ColorExtractor\Palette;


    class Post {
        private $title;
        private $description;
        private $tags;
        private $image;
        private $colors;

        public function getTitle(){return $this->title;}

        public function setTitle($title)
        {
            $title = Cleaner::cleanInput($title);
            $this->title = $title;
            return $this;
        }

        public function getDescription(){return $this->description;}

        public function setDescription($description)
        {
            $description = Cleaner::cleanInput($description);
            $this->description = $description;
            return $this;
        }

        public function getTags(){return $this->tags;}

        public function setTags($tags)
        {
            $tags = Cleaner::cleanInput($tags);
            $tags = str_replace(' ', '', $tags);
            $tags = explode(",", $tags);
            $this->tags = json_encode($tags);
            return $this;
        }

        public function getImage(){return $this->image;}

        public function setImage($image)
        {
            $image = Cleaner::cleanInput($image);
            $this->image = $image;
            return $this;
        }
        
        public function getColors(){return $this->colors;}

        public function setColors(){
            $colorArray = [];
            $palette = Palette::fromFilename($this->image);
            $topFive = $palette->getMostUsedColors(5);
            foreach($topFive as $color) {
                array_push($colorArray, Color::fromIntToHex($color));
            }
            $this->colors = json_encode($colorArray);
            return $this;
        }

        public function addPost($user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into posts (title, user_id, image, colors, description, tags, creation_date) values (:title, :user_id, :image, :colors, :description, :tags, :creation_date);");
            // $statement = $conn->prepare("insert into posts (title, user_id, image, description, tags) values (:title, :user_id, :image, :description, :tags);");
            $statement->bindValue(':title', $this->title);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':image', $this->image);
            $statement->bindValue(':colors', $this->colors);
            $statement->bindValue(':description', $this->description);
            $statement->bindValue(':tags', $this->tags);
            $statement->bindValue(':creation_date', $this->getDateTime());
            $res = $statement->execute();
            return $res;
        }

        private function getDateTime(){
            $dateTime = new DateTime();
            $dateTime = $dateTime->format('Y-m-d H:i:s');
            return $dateTime;
        }

        public static function getPostsCount(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts order by creation_date");
            $statement->execute();
            // $res = $statement->fetchAll();
            $res = $statement->rowCount();
            return $res;
        }

        public static function getSomePosts($sorting, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts order by creation_date $sorting limit $start, $amount");
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }
        
        public static function getFollowedPosts($uid, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where user_id in (select follower_id from follows where user_id = :user_id) order by creation_date desc limit $start, $amount");
            $statement->bindValue(":user_id", $uid);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public function getPostbyId($id, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where user_id = :user_id order by creation_date desc limit $start, $amount");
            $statement->bindValue('user_id', $id);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getSearchPosts($search, $sort, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search order by creation_date :sort limit $start, $amount ");
            $statement->bindValue(':search', '%' . $search . '%' , PDO::PARAM_STR);
            $statement->bindValue(':sort', $sort);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getFollowedSearchPosts($uid, $search, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search and where user_id in (select follower_id from follows where user_id = :user_id) order by creation_date desc limit $start, $amount ");
            $statement->bindValue(':search', '%' . $search . '%' , PDO::PARAM_STR);
            $statement->bindValue(':user_id', $uid);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function deletePostById($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from posts where id = :post_id");
            $statement->bindValue('post_id', $postId);
            $statement->execute();
        }

        public function updatePostById($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update posts set title = :title, description = :description, tags = :tags where id = :post_id");
            $statement->bindValue('title', $this->getTitle());
            $statement->bindValue('description', $this->getDescription());
            $statement->bindValue('tags', $this->getTags());
            $statement->bindValue('post_id', $postId);
            $statement->execute();
        }

        public static function getPostByPostId($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where id = :post_id");
            $statement->bindValue('post_id', $postId);
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

    }