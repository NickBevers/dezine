<?php
    namespace Dezine\Auth;
    use Dezine\Helpers\Cleaner;
    use Dezine\Content\UploadImage;
    use Exception;
    use PDO;

    class User {
        private $username;
        private $email;
        private $password;
        private $bio;
        private $education;
        //profile image
        private $profile_image;
        private $profile_image_public_id;
        //social links
        private $linkedin;
        private $website;
        private $instagram;
        private $github;
        //second email
        private $second_email;
        const PASSWORD_MIN_LENGTH = 6;
        //role
        private $role;

        public function getUsername(){return $this->username;}

        public function setUsername($username)
        {
            $username = Cleaner::cleanInput($username);
            $this->username = $username;
            return $this;
        }

        //emails setters and getters
        public function getEmail(){return $this->email;}
        
        public function setEmail($email)
        {
            $email = Cleaner::cleanInput($email);
            $this->email = $email;
            return $this;
        }

        public function getSecondEmail(){return $this->second_email;}
        
        public function setSecondEmail($second_email)
        {
            $second_email = Cleaner::cleanInput($second_email);
            $this->second_email = $second_email;
            return $this;
        }

        //profile_image
        public function getProfileImage(){return $this->profile_image;}

        public function setProfileImage($profile_image)
        {
            $profile_image = Cleaner::cleanInput($profile_image);
            $this->profile_image = $profile_image;
            return $this;
        }

        //profile_image_public_id
        public function getProfileImagePublicId(){return $this->profile_image_public_id;}

        public function setProfileImagePublicId($profile_image_public_id){
            $this->profile_image_public_id = $profile_image_public_id;
            return $this;
        }

        //password
        public function getPassword(){return $this->password;}

        public function setPassword( $password )
        {
            $password = Cleaner::cleanInput($password);
            if(strlen($password) < self::PASSWORD_MIN_LENGTH){
                throw new Exception("Passwords must be " . self::PASSWORD_MIN_LENGTH . " characters or longer.");
            }
            $this->password = $password;
            return $this;
        }

        //about getters and setters
        public function getBio(){return $this->bio;}

        public function setBio($bio)
        {
            $bio = Cleaner::cleanInput($bio);
            $this->bio = $bio;
            return $this;
        }

        public function getEducation(){return $this->education;}

        public function setEducation($education)
        {
            $education = Cleaner::cleanInput($education);
            $this->education = $education;
            return $this;
        }

        //socials getters and setters
        public function getLinkedin(){return $this->linkedin;}

        public function setLinkedin($linkedin)
        {
            $linkedin = Cleaner::cleanInput($linkedin);
            $this->linkedin = $linkedin;
            return $this;
        }

        public function getWebsite(){return $this->website;}

        public function setWebsite($website)
        {
            $website = Cleaner::cleanInput($website);
            $this->website = $website;
            return $this;
        }

        public function getInstagram(){return $this->instagram;}

        public function setInstagram($instagram)
        {
            $instagram = Cleaner::cleanInput($instagram);
            $this->instagram = $instagram;
            return $this;
        }

        public function getGithub(){return $this->github;}

        public function setGithub($github)
        {
            $github = Cleaner::cleanInput($github);
            $this->github = $github;
            return $this;
        }

        public function getRole()
        {
            return $this->user_role;
        }

        public function setRole($user_role)
        {
            $this->user_role = $user_role;

            return $this;
        }

        public function canLogin() {
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email OR second_email = :email");
            $statement->bindValue(':email', $this -> email);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);

            $regex = '/[a-zA-Z0-9_.+-]+@(student\.)?thomasmore\.be/';
            if(!preg_match($regex, $this->email)){throw new Exception("Please use your Thomas More account to log in");}

            if(!$res){
                throw new Exception("No user was found with this email");
            }

            if(password_verify($this->password, $res["password"])){
                return $res;
            }

            throw new Exception("This password does not match the given email");
        }

        public function register($referLink = "") {
            if(!$this->userExists()){
                if(strlen($referLink) === 0){
                    $regex = '/[a-zA-Z0-9_.+-]+@(student\.)?thomasmore\.be/';
                    if(!preg_match($regex, $this->email)){throw new Exception("Please use your Thomas More account to register");}
                }
                $options = [
                'cost' => 15
                ];
                $password = password_hash($this->password, PASSWORD_DEFAULT, $options);

                $conn = DB::getInstance();
                $statement = $conn->prepare("insert into users (username, email, password, user_role, profile_image) values (:username, :email, :password, 'user', :profile_image);");
                $statement->bindValue(':username', $this->username);
                $statement->bindValue(':email', $this->email);
                $statement->bindValue(':password', $password);
                $statement->bindValue(':profile_image', $this->profile_image);
                $statement->execute();
                $res = $conn->lastInsertId();
                return $res;
            } else{
                throw new Exception("This email address is already in use");
            }
        }

        public function userExists(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue(':email', $this->email);
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

        public function usernameExists(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where username = :username");
            $statement->bindValue(':username', $this->username);
            $statement->execute();
            $res = $statement->fetch();
            return $res;
        }

        public static function resetPassword($email, $c_password, $new_password){

            if(strlen(Cleaner::cleanInput($new_password)) < self::PASSWORD_MIN_LENGTH){
                throw new Exception("Passwords must be " . self::PASSWORD_MIN_LENGTH . " characters or longer.");
            } else{
                $conn = DB::getInstance();
                $statement = $conn->prepare("select * from users where email = :email");
                $statement->bindValue(':email', Cleaner::cleanInput($email));
                $statement->execute();
                $res = $statement->fetch();

                $options = [
                'cost' => 15
                ];
                $n_password = password_hash(Cleaner::cleanInput($new_password), PASSWORD_DEFAULT, $options);

                if (password_verify($c_password, $res["password"])) {
                    $statement = $conn->prepare("update users set password = :password where email = :email");
                    $statement->bindValue(':password', $n_password);
                    $statement->bindValue(':email', Cleaner::cleanInput($email));
                    $statement->execute();
                } else {
                    throw new Exception("The given password does not match the password");
                }
            }
        }

        public static function deleteUserContentById($id){
            // remove comments
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from comments where user_id = :id");
            $statement->bindValue(':id', $id);
            $statement->execute();

            $stmt = $conn->prepare("select * from posts where user_id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($res);
            // die();
            foreach($res as $post){if(!empty($post["public_id"])){UploadImage::remove($post["public_id"]);}}
            
            // remove posts
            $statement2 = $conn->prepare("delete from posts where user_id = :id");
            $statement2->bindValue(':id', $id);
            $statement2->execute();

            //remove likes
            $statement3 = $conn->prepare("delete from likes where user_id = :id");
            $statement3->bindValue(':id', $id);
            $statement3->execute();

            //remove follows
            $statement3 = $conn->prepare("delete from follows where user_id = :id");
            $statement3->bindValue(':id', $id);
            $statement3->execute();

            //remove showcase posts
            $statement3 = $conn->prepare("delete from showcase where user_id = :id");
            $statement3->bindValue(':id', $id);
            $statement3->execute();
        }

        public static function deleteUserByEmail($userEmail) {
            $conn = DB::getInstance();
            $stmt = $conn->prepare("select * from users where email = :email");
            $stmt->bindValue(':email', $userEmail);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!empty($res["profile_image_public_id"])){
                UploadImage::remove($res["profile_image_public_id"]);
            }

            $statement = $conn->prepare("delete from users where email = :email");
            $statement->bindValue(':email', $userEmail);
            $statement->execute();
        }

        public function updateUser(){            
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set username = :username, education = :education, bio = :bio, linkedin = :linkedin, website = :website, instagram = :instagram, github = :github, second_email =:second_email, profile_image =:profile_image, profile_image_public_id = :profile_image_public_id where email = :email");
            $statement->bindValue(':username',$this->username);
            $statement->bindValue(':profile_image', $this->profile_image);
            $statement->bindValue(':profile_image_public_id', $this->profile_image_public_id);
            $statement->bindValue(':education', $this->education);
            $statement->bindValue(':bio', $this->bio);
            $statement->bindValue(':linkedin',$this->linkedin);
            $statement->bindValue(':website', $this->website);
            $statement->bindValue(':instagram', $this->instagram);
            $statement->bindValue(':github', $this->github);
            $statement->bindValue(':second_email', $this->second_email);
            $statement->bindValue(':email', $this->email);
            return $statement->execute();
        }

        public static function getUser($email){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select username, education, bio, linkedin, website, instagram, github, second_email, profile_image from users where email = :email");
            $statement->bindValue(':email', Cleaner::cleanInput($email));
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }

        public static function getUserbyId($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($id));
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }

        public static function getUserNamebyId($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select username from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($id));
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }

        public static function checkModerator($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select user_role from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($userId));
            $statement->execute();
            $result = $statement->fetch();
            if($result["user_role"] === "moderator" || $result["user_role"] === "admin"){
                return true;
            }
            else{
                return false;
            }
        }

        public static function checkBan($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select banned from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($userId));
            $statement->execute();
            $result = $statement->fetch();
            return $result["banned"];
        }

        public static function addBan($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set banned = 1 where id = :id");
            $statement->bindValue(':id', $userId);
            $statement->execute();
            $message = "User has been banned";
            return $message;
        }

        public static function removeBan($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set banned = 0 where id = :id");
            $statement->bindValue(':id', $userId);
            $statement->execute();
            $message = "The ban has been lifted";
            return $message;
        }
      
        public static function checkUserRole($uid){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($uid));
            $statement->execute();
            $result = $statement->fetch();
            return $result["user_role"];
        }

        public static function UpdateUserRole($role, $uid){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set user_role = :role where id = :uid");
            $statement->bindValue(':role', Cleaner::cleanInput($role));
            $statement->bindValue(':uid', Cleaner::cleanInput($uid));
            $statement->execute();
        }

        public static function checkWarning($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from warnings where user_id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($userId));
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }

        public static function getAllUsers(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users");
            $statement->execute();
            $res = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        public static function getProfileImagebyId($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select profile_image from users where id = :id");
            $statement->bindValue(':id', Cleaner::cleanInput($id));
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }
    }