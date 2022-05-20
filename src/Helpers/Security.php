<?php
    namespace Dezine\Helpers;
    abstract class Security {
        public static function isLoggedIn(){

            if(isset($_SESSION['email'])){
                return true;
            }
            return false;
        }
    }