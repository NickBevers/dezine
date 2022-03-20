<?php
    abstract class Security {
        public static function onlyLoggedInUsers() {
            session_start();
            if(!isset($_SESSION['user'])){
                header("Location: login.php");
            }
        }
    }