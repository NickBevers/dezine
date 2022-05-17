<?php 

    require "vendor/autoload.php";
    $config = parse_ini_file(__DIR__ . "/../config/config.ini");
    use Cloudinary\Api\Upload\UploadApi;
    use \Cloudinary\Configuration\Configuration;


    Configuration::instance([
        'cloud' => [
          'cloud_name' => $config["cloud_name"], 
          'api_key' => $config["api_key"], 
          'api_secret' => $config["api_secret"]],
        'url' => [
          'secure' => true]]);

        //   \Cloudinary\Api\Upload::upload("https://upload.wikimedia.org/wikipedia/commons/a/ae/Olympic_flag.jpg", ["public_id" => "olympic_flag"]);
    class UploadImage{


        public function upload($file, $options = []){
            // new UploadApi()->upload('dog.mp4', [
            //     'folder' => 'myfolder/mysubfolder/', 
            //     'public_id' => 'my_dog', 
            //     'overwrite' => true, 
            //     'notification_url' => 'https://mysite.example.com/notify_endpoint', 
            //     'resource_type' => 'video']);
        }

    }

