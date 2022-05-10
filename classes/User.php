<?php
    include_once(__DIR__ . "/../autoloader.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");


    class User {
        private $username;
        private $email;
        private $password;
        private $bio;
        private $education;
        //profile image
        private $profile_image;
        //social links
        private $linkedin;
        private $website;
        private $instagram;
        private $github;
        //second email
        private $second_email;
        const PASSWORD_MIN_LENGTH = 6;

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

        public function canLogin() {
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email OR second_email = :email");
            $statement->bindValue(':email', $this -> email);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);

            if(!$res){
                throw new Exception("No user was found with this email");
            }

            if(password_verify($this->password, $res["password"])){
                return $res;
            }

            throw new Exception("This password does not match the given email");
        }

        public function register() {
            if(!$this->userExists()){
                $regex = '/[a-zA-Z0-9_.+-]+@(student\.)?thomasmore\.be/';
                if(!preg_match($regex, $this->email)){throw new Exception("Please use your Thomas More account to register");}
                $options = [
                'cost' => 15
                ];
                $password = password_hash($this->password, PASSWORD_DEFAULT, $options);

                $conn = DB::getInstance();
                $statement = $conn->prepare("insert into users (username, email, password) values (:username, :email, :password);");
                $statement->bindValue(':username', $this->username);
                $statement->bindValue(':email', $this->email);
                $statement->bindValue(':password', $password);
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

            if(strlen($new_password) < self::PASSWORD_MIN_LENGTH){
                throw new Exception("Passwords must be " . self::PASSWORD_MIN_LENGTH . " characters or longer.");
            } else{
                $conn = DB::getInstance();
                $statement = $conn->prepare("select * from users where email = :email");
                $statement->bindValue(':email', $email);
                $statement->execute();
                $res = $statement->fetch();
                // var_dump($res);

                $options = [
                'cost' => 15
                ];
                $n_password = password_hash($new_password, PASSWORD_DEFAULT, $options);

                if (password_verify($c_password, $res["password"])) {
                    $statement = $conn->prepare("update users set password = :password where email = :email");
                    $statement->bindValue(':password', $n_password);
                    $statement->bindValue(':email', $email);
                    $statement->execute();
                    // $result = $statement->fetch();
                    // var_dump($result);
                } else {
                    throw new Exception("The given password does not match the password");
                }
            }
        }

        public static function deleteUserContentByEmail($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from comments where user_id = :id");
            $statement->bindValue(':id', $id);
            $statement->execute();

            $statement2 = $conn->prepare("delete from posts where user_id = :id");
            $statement2->bindValue(':id', $id);
            $statement2->execute();

            $statement3 = $conn->prepare("delete from likes where user_id = :id");
            $statement3->bindValue(':id', $id);
            $statement3->execute();
        }

        public static function deleteUserByEmail($userEmail) {
            $conn = DB::getInstance();
            $statement = $conn->prepare("delete from users where email = :email");
            $statement->bindValue(':email', $userEmail);
            $statement->execute();
        }

        public function updateUser(){
            
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set username = :username, education = :education, bio = :bio, linkedin = :linkedin, website = :website, instagram = :instagram, github = :github, second_email =:second_email, profile_image =:profile_image where email = :email");
            $statement->bindValue(':username',$this->username);
            $statement->bindValue(':profile_image', $this->profile_image);
            $statement->bindValue(':education', $this->education);
            $statement->bindValue(':bio', $this->bio);
            $statement->bindValue(':linkedin',$this->linkedin);
            $statement->bindValue(':website', $this->website);
            $statement->bindValue(':instagram', $this->instagram);
            $statement->bindValue(':github', $this->github);
            $statement->bindValue(':second_email', $this->second_email);
            $statement->bindValue(':email', $this->email);
            $statement->execute();
            return $this->getUser();
        }

        public function getUser(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select username, education, bio, linkedin, website, instagram, github, second_email, profile_image from users where email = :email");
            $statement->bindValue(':email', $this->email);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }

        public static function getUserbyId($id){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where id = :id");
            $statement->bindValue(':id', $id);
            $statement->execute();
            $result = $statement->fetch();
            // var_dump($result);
            return $result;
        }

        public static function checkModerator($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select user_role from users where id = :id");
            $statement->bindValue(':id', $userId);
            $statement->execute();
            $result = $statement->fetch();
            // var_dump($result["user_role"]);
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
            $statement->bindValue(':id', $userId);
            $statement->execute();
            $result = $statement->fetch();
            // var_dump($result);
            return $result["banned"];
        }

        public static function addBan($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set banned = 1 where id = :id");
            $statement->bindValue(':id', $userId);
            $statement->execute();
            // var_dump($statement->execute());
            // $result = $statement->fetch();
            // var_dump($result["banned"]);
            // return $result;
            $message = "User has been banned";
            return $message;
        }

        public static function removeBan($userId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("update users set banned = 0 where id = :id");
            $statement->bindValue(':id', $userId);
            $statement->execute();
            // var_dump($statement->execute());
            $message = "The ban has been lifted";
            return $message;
        }
    }