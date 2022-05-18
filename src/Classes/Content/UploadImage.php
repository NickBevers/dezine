<?php
    namespace Classes\Content;

    use Exception;
    use Cloudinary\Api\Upload\UploadApi;
    use \Cloudinary\Configuration\Configuration;

    require "vendor/autoload.php";
    $config = parse_ini_file(__DIR__ . "/../../../config/config.ini");
    
    Configuration::instance([
        'cloud' => [
          'cloud_name' => 'd-zine', 
          'api_key' => '218981663968882', 
          'api_secret' => 'xltQwLEBRnur7IVbViRrtoG9Bo4'],
        'url' => [
          'secure' => true]]);

            
    class UploadImage
    {
        public static function uploadPostPic($file)
        {
            $uApi = new UploadApi();
            $upload = $uApi->upload($file, ['folder' => 'posts/', 'resource_type' => 'image']);
            return $upload;
        }

        public static function uploadProfilePic($file)
        {
            $uApi = new UploadApi();
            $upload = $uApi->upload($file, ['folder' => 'profiles/', 'resource_type' => 'image']);
            return $upload;
        }

        public function remove($public_id)
        {
            $rApi = new UploadApi();
            $remove = $rApi->destroy($public_id, ['invalidate' => true]);
            return $remove;
        }

        public static function getImageData($image, $tmpName, $user_id)
        {
            if (empty($image)) {
                throw new Exception("Please upload an image before submitting");
            }
            $fileName = $user_id . str_replace(" ", "_", basename($image));
            $fileType = pathinfo($image)["extension"];
            $tempPath = "uploads/" . $fileName;
            $allowedFileTypes = array('jpg','png','jpeg','gif', 'jfif', 'webp');

            if (!in_array($fileType, $allowedFileTypes)) {
                throw new Exception("This file type is not supported, please upload a jpg, png, gif or webp file.");
            }

            if (!move_uploaded_file($tmpName, $tempPath)) {
                throw new Exception("The file could not be uploaded, please try again");
            } else {
                return $tempPath;
            }
        }
    }
