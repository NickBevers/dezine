<?php
    abstract class CheckEmpty {
        public static function isNotEmpty($field) {
            if(!empty($field)){
                return true;
            } return false;
        }
    }