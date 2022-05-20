<?php
    namespace Dezine\Helpers;
    abstract class Cleaner {
        public static function cleanInput($input) {
            $input = trim($input);
            $input = stripslashes($input);
            // $input = htmlspecialchars($input);
            return $input;
        }

        public static function xss($input){
            if(gettype($input) !== 'array'){
                return htmlspecialchars(stripslashes($input), ENT_NOQUOTES);
            } else{
                foreach($input as $key => $arrItem){
                    $arrItem = self::xss($arrItem);
                    $input[$key] = $arrItem;
                }
            }
            return $input;
        }
    }