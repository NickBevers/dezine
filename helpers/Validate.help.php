<?php
    abstract class Validate {
        public static function isNotEmpty($field) {
            if(!empty($field)){
                return true;
            } return false;
        }

        public static function start(){
            session_start();
        }

        public static function end(){
            session_destroy();
        }
    }