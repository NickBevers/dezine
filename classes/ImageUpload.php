<?php 

    require "vendor/autoload.php";
    $config = parse_ini_file(__DIR__ . "/../config/config.ini");
    use \Cloudinary\Api\Upload;
    use \Cloudinary\Configuration\Configuration;


    Configuration::instance($config["cloudinary"]);

    class ImageUpload{


    }

