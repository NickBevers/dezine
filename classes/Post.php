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
            $this->tags = $tags;
            return $this;
        }

        public function getImage(){return $this->image;}

        public function setImage($image)
        {
            $image = Cleaner::cleanInput($image);
            $this->image = $image;
            return $this;
        }

        public function addPost(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("insert into posts (user_id, image, description, tags) values (':user_id', ':image', ':description', ':tags')");
        }
    }