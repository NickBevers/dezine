<?php
    abstract class Security {
       /* public static function onlyLoggedInUsers() {
            session_start();
            if(!isset($_SESSION['email'])){
                header("Location: login.php");
            }
        }

        public static function alreadyLoggedIn(){
            session_start();
            if(isset($_SESSION['email'])){
                header("Location: home.php");
            }
        }*/

        public static function isLoggedIn(){
            session_start();
            if(isset($_SESSION['email'])){
                return true;
            }
            return false;
        }
    }