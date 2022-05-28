<?php
    namespace Dezine\Auth;
    use PDO;

    abstract class DB {
        private static $conn;

        public static function getInstance(){
            if(self::$conn != null){
                return self::$conn;
            } else{
                $config = parse_ini_file(__DIR__ . "/../../config/config.ini");
                self::$conn = new PDO('mysql:host='. $config['db_host'] .';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);
                return self::$conn;
            }
        }        
    }