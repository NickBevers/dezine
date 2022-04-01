<?php
    abstract class Security {

        public static function isLoggedIn(){
            session_start();
            if(isset($_SESSION['email'])){
                return true;
            }
            return false;
        }
    }