<?php
    include_once(__DIR__ . "/DB.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");


    class Search {

        private $title;
        private $tags;
        
        public function getTitle(){return $this->title;}

        public function getTags(){return $this->tags;}

        public static function getSearchPost($search_keyword){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from posts where `title` like :keyword or description like :keyword order by id desc ");
            $statement->bindValue(':keyword', '%' . $search_keyword . '%' , PDO::PARAM_STR);
            $statement->execute();
            $res = $statement->fetchAll();
            return $res;
        }



}