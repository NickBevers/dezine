<?php 
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    require 'vendor/autoload.php';
    use League\ColorExtractor\Color;
    use League\ColorExtractor\ColorExtractor;
    use League\ColorExtractor\Palette;
    use PHPColorExtractor\PHPColorExtractor;


    class Post {
        private $title;
        private $description;
        private $tags;
        private $image;
        private $colors;
        private $color_groups;

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
            $extractor = new PHPColorExtractor();
            $extractor->setImage($this->getImage())->setTotalColors(5)->setGranularity(10);
            $palette = $extractor->extractPalette();
            $colours = [];
            $color_groups = [];
            foreach($palette as $color) {
                $hslVal = $this->hexToHsl($color);
                $color_group = $this->getColorGroupFromColor($hslVal);
                array_push($color_groups, $color_group);
                array_push($colours, $hslVal);
            }
            $this->colors = json_encode($colours);
            return $this;
        }

        public function getColor_groups(){
            return $this->color_groups;
        }

        public function setColor_groups($color_groups){
            $this->color_groups = $color_groups;
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
            $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search and where user_id in (select follower_id from follows where user_id = :user_id) order by creation_date :sort limit $start, $amount ");
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

        private function hexToHsl($hex){
            // Taken from https://stackoverflow.com/questions/46432335/hex-to-hsl-convert-javascript and converted to correct php code. 
            $hex = "#" . $hex;
            $result = "/#([[:xdigit:]]{3}){1,2}\b/";
            if(preg_match($result, $hex)){
                $r = intval(substr($hex, 1, 2), 16);
                $g = intval(substr($hex, 3, 2), 16);
                $b = intval(substr($hex, 5, 2), 16);
            
                $r /= 255; 
                $g /= 255; 
                $b /= 255;
                $max = max($r, $g, $b); 
                $min = min($r, $g, $b);
                $h = $s = $l = ($max + $min) / 2;
            
                if($max == $min){
                    $h = $s = 0;
                } else {
                    $d = $max - $min;
                    $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                    switch($max) {
                        case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                        case $g: $h = ($b - $r) / $d + 2; break;
                        case $b: $h = ($r - $g) / $d + 4; break;
                    }
                    $h /= 6;
                }
            
                $h = round(360*$h);
                $s = round(100*$s);
                $l = round(100*$l);
            
                return 'hsl(' . $h . ',' . $s . '%,' . $l . '%)';
            } else{
                throw new Error("The colour you gave is not valid, please try again");
            }
        }

        private function getColorGroupFromColor($hsl){
            $hsl = str_replace("hsl(", "", $hsl);
            $hsl = str_replace(")", "", $hsl);
            $hsl = explode(", ", $hsl);
            $h = $hsl[0];
            $s = str_replace("%", "", $hsl[1]);
            $l = str_replace("%", "", $hsl[2]);
        }
    }