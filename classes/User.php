<?php
    include_once(__DIR__ . "/DB.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");


    class User {
        private $username;
        private $email;
        private $password;
        const PASSWORD_MIN_LENGTH = 6;

        public function getUsername(){return $this->username;}

        public function setUsername($username)
        {
            $username = Cleaner::cleanInput($username);
            $this->username = $username;
            return $this;
        }
        
        public function getEmail(){return $this->email;}
        
        public function setEmail($email)
        {
            $email = Cleaner::cleanInput($email);
            $this->email = $email;
            return $this;
        }

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
                return true;
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
                $options = [
                    'cost' => 15
                  ];
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

        private function userExists(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue(':email', $this->email);
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
                    $statement = $conn->prepare("update users set password= :password where email= :email");
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

        public static function deleteUserByEmail($userEmail) {
            $conn = DB::getInstance();

            $statement = $conn->prepare("DELETE FROM users WHERE email = :email;");
            $statement->bindValue(':email', $userEmail);
            $result = $statement->execute();
        }
    }