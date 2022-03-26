<?php 
    include_once(__DIR__ . "/DB.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");


    class Post {
        private $title;
        private $description;
        private $tags;
        private $image;

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

        public function addPost($user_id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into posts (title, user_id, image, description, tags) values (:title, :user_id, :image, :description, :tags);");
            // $statement = $conn->prepare("INSERT INTO posts ('title', 'user_id', 'image', 'description', 'tags') VALUES (':title', ':user_id', ':image', ':description', ':tags')");
            $statement->bindValue(":title", $this->title);
            $statement->bindValue(":user_id", $user_id);
            $statement->bindValue(":image", $this->image);
            $statement->bindValue(":description", $this->description);
            $statement->bindValue(":tags", $this->tags);
            $statement->execute();
        }
    }