<?php 
    namespace Dezine\Content;
    use \Dezine\Helpers\Cleaner;
    use \Dezine\Auth\DB;
    use \PHPColorExtractor\PHPColorExtractor;
    use DateTime;
    use PDO;
    use Error;
    use Exception;

    class Post {
        private $title;
        private $description;
        private $tags;
        private $public_id;
        private $image;
        private $colors;
        private $color_groups;

        public function getTitle(){return $this->title;}

        public function setTitle($title){
            $title = Cleaner::cleanInput($title);
            if(empty($title)){
                throw new Exception("Your title seems to be missing, please fill in the field.");
            }else{
                $this->title = $title;
                return $this;
            }
        }

        public function getDescription(){return $this->description;}

        public function setDescription($description){
            $description = Cleaner::cleanInput($description);
            if(empty($description)){
                throw new Exception("Your description seems to be missing, please fill in the field.");
            }else{
                $this->description = $description;
                return $this;
            }
        }

        public function getTags(){return $this->tags;}

        public function setTags($tags){
            $tags = Cleaner::cleanInput($tags);
            if(empty($tags)){
                throw new Exception("Your tags seem to be missing, please fill in the field.");
            }else{
                $tags = str_replace(' ', '', $tags);
                $tags = explode(",", $tags);
                $this->tags = json_encode($tags);
                return $this;
            }
        }

        public function getPublic_id(){return $this->public_id;}

        public function setPublic_id($public_id){
            $public_id = Cleaner::cleanInput($public_id);
            $this->public_id = $public_id;
            return $this;
        }

        public function getImage(){return $this->image;}

        public function setImage($image){
            $image = Cleaner::cleanInput($image);
            $this->image = $image;
            return $this;
        }
        
        public function getColors(){return $this->colors;}

        public function setColors($file_path){
            $extractor = new PHPColorExtractor();
            $extractor->setImage($file_path)->setTotalColors(5)->setGranularity(10);
            $palette = $extractor->extractPalette();
            $colours = [];
            $color_groups = [];
            
            foreach($palette as $color){
                $hslVal = $this->hexToHsl($color);
                $color_group = $this->getColorGroupFromColor($hslVal);
                array_push($color_groups, $color_group);
                array_push($colours, $hslVal);
            }
            
            $this->colors = json_encode($colours);
            $this->color_groups = json_encode($color_groups);
            return $this;
        }

        public function getColor_groups(){
            return $this->color_groups;
        }

        public function setColor_groups($color_groups){
            $color_groups = Cleaner::cleanInput($color_groups);
            $this->color_groups = $color_groups;
            return $this;
        }

        public static function getPostbyPostId($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where id = :post_id");
            $statement->bindValue('post_id', Cleaner::cleanInput($id));
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

        public function addPost($user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into posts (title, user_id, image, public_id, colors, color_group, description, tags, creation_date) values (:title, :user_id, :image, :public_id, :colors, :color_group, :description, :tags, :creation_date);");
            $statement->bindValue(':title', $this->title);
            $statement->bindValue(':user_id', Cleaner::cleanInput($user_id));
            $statement->bindValue(':image', $this->image);
            $statement->bindValue(':public_id', $this->public_id);
            $statement->bindValue(':colors', $this->colors);
            $statement->bindValue(':color_group', $this->color_groups);
            $statement->bindValue(':description', $this->description);
            $statement->bindValue(':tags', $this->tags);
            $statement->bindValue(':creation_date', $this->getDateTime());
            $res = $statement->execute();
            var_dump($res);
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
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
        
        public static function getFollowedPosts($uid, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where user_id in (select follower_id from follows where user_id = :user_id) order by creation_date desc limit $start, $amount");
            $statement->bindValue(":user_id", Cleaner::cleanInput($uid));
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getPostbyId($id, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where user_id = :user_id order by creation_date desc limit $start, $amount");
            $statement->bindValue('user_id', Cleaner::cleanInput($id));
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getPostsByColor($color, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where color_group like :color order by creation_date desc limit $start, $amount");
            $statement->bindValue(':color', "%" . Cleaner::cleanInput($color) . "%", PDO::PARAM_STR);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getSearchPosts($search, $sort, $start, $amount){
            $conn = DB::getInstance();
            switch($sort){
                case "desc":
                    $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search order by creation_date desc limit $start, $amount");
                break;
                case "asc":
                    $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search order by creation_date asc limit $start, $amount");
                break;
                default:
                    $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search order by creation_date desc limit $start, $amount");
                break;
            }
            $statement->bindValue(':search', '%' . Cleaner::cleanInput($search) . '%'); //, PDO::PARAM_STR
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        public static function getFollowedSearchPosts($uid, $search, $start, $amount){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where title like :search or description like :search or tags like :search and where user_id in (select follower_id from follows where user_id = :user_id) order by creation_date desc limit $start, $amount ");
            $statement->bindValue(':search', '%' . Cleaner::cleanInput($search) . '%' , PDO::PARAM_STR);
            $statement->bindValue(':user_id', Cleaner::cleanInput($uid));
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function deletePostById($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from posts where id = :post_id");
            $statement->bindValue('post_id', Cleaner::cleanInput($postId));
            $statement->execute();
        }

        public function updatePostById($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update posts set title = :title, description = :description, tags = :tags where id = :post_id");
            $statement->bindValue('title', $this->getTitle());
            $statement->bindValue('description', $this->getDescription());
            $statement->bindValue('tags', $this->getTags());
            $statement->bindValue('post_id', Cleaner::cleanInput($postId));
            $statement->execute();
        }

        public static function getMostUsedTags(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select tags, count(*) from posts group by tags order by count(*) desc limit 10");
            $statement->execute();
            $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
            return self::getTenTags($tags);
        }

        private static function getTenTags($tagsPerPost){
            $tagArr = array();
            foreach($tagsPerPost as $tags){
                $tagsArr = json_decode($tags["tags"]);
                foreach($tagsArr as $tag){
                    array_push($tagArr, $tag);
                }
            }
            $tagArr = array_count_values($tagArr);
            foreach($tagArr as $key => $value){$tagArr[$key] = strval($value);}
            arsort($tagArr);
            return array_slice($tagArr, 0, 10);
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

        private function getColorfromHue($hVal){
            switch ($hVal){
                case $hVal < 15 || $hVal >= 345: return "red"; break;
                case $hVal < 40 && $hVal >= 15: return "orange"; break;
                case $hVal < 60 && $hVal >= 40: return "yellow"; break;
                case $hVal < 75 && $hVal >= 60: return "lime"; break;
                case $hVal < 140 && $hVal >= 75: return "green"; break;
                case $hVal < 180 && $hVal >= 140: return "aqua"; break;
                case $hVal < 260 && $hVal >= 180: return "blue"; break;
                case $hVal < 285 && $hVal >= 260: return "purple"; break;
                case $hVal < 345 && $hVal >= 285: return "pink"; break;
            }
        }

        private function getColorGroupFromColor($hsl){
            $hsl = str_replace("hsl(", "", $hsl);
            $hsl = str_replace(")", "", $hsl);
            $hsl = explode(",", $hsl);
            $h = intval($hsl[0]);
            $s = intval(str_replace("%", "", $hsl[1]));
            $l = intval(str_replace("%", "", $hsl[2]));

            if($s <= 10){
                switch ($l){
                    case $l == 0: return "black"; break;
                    case $l > 0 && $l < 15: return "black"; break;
                    case 15 <= $l && $l < 40: return "dark gray"; break;
                    case 40 <= $l && $l < 75: return "gray"; break;
                    case 75 <= $l && $l < 90: return "light gray"; break;
                    case $l >= 90: return "white"; break;
                }
            } else if($s > 10 && $s <= 30){
                switch ($l){
                    case $l >= 80: return "white"; break;
                    case 65 <= $l && $l < 80: return "light " . $this->getColorfromHue($h); break;
                    case 30 <= $l && $l < 65: return $this->getColorfromHue($h); break;
                    case 15 <= $l && $l < 30: return "dark " . $this->getColorfromHue($h); break;
                    case $l < 15: return "black"; break;
                } 
            } else if($s > 30 && $s <= 55){
                switch ($l){
                    case $l >= 90: return "white"; break;
                    case 65 <= $l && $l < 90: return "light " . $this->getColorfromHue($h); break;
                    case 30 <= $l && $l < 65: return $this->getColorfromHue($h); break;
                    case 15 <= $l && $l < 30: return "dark " . $this->getColorfromHue($h); break;
                    case $l < 15: return "black"; break;
                }
            } else if($s > 55){
                switch ($l){
                    case $l >= 95: return "white"; break;
                    case 75 <= $l && $l < 95: return "light " . $this->getColorfromHue($h); break;
                    case 35 <= $l && $l < 75: return $this->getColorfromHue($h); break;
                    case 10 <= $l && $l < 35: return "dark " . $this->getColorfromHue($h); break;
                    case $l < 10: return "black"; break;
                }
            }
        }

        public static function addViewbyPost($postId, $userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into views (user_id, post_id) values (:user_id,:post_id)");
            $statement->bindValue('user_id', Cleaner::cleanInput($userId));
            $statement->bindValue('post_id', Cleaner::cleanInput($postId));
            $statement->execute();
            // var_dump($statement->execute());
        }

        public static function getViewsbyPost($postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from views where post_id = :post_id");
            $statement->bindValue('post_id', Cleaner::cleanInput($postId));
            $statement->execute();
            $res = $statement->rowCount();
            // var_dump($res + 1);
            return $res;
        }

        public static function getViewsbyId($userId, $postId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from views where user_id = :user_id and post_id = :post_id");
            $statement->bindValue('post_id', Cleaner::cleanInput($postId));
            $statement->bindValue('user_id', Cleaner::cleanInput($userId));
            $statement->execute();
            $res = $statement->fetch();
            // var_dump($res);
            return $res;
        }

        public static function getAllPosts(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts");
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }

        public static function getPostsCountbyId($uid){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts order by creation_date where user_id = :user_id");
            $statement->bindValue('user_id', Cleaner::cleanInput($uid));
            $statement->execute();
            // $res = $statement->fetchAll();
            $res = $statement->rowCount();
            return $res;
        }
    }