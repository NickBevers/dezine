<?php
    abstract class Cleaner {
        public static function cleanInput($input) {
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }
    }