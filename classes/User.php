<?php
    include_once(__DIR__ . "/DB.php");

    class User {
        private $username;
        private $email;
        private $password;

        public function getUsername(){return $this->username;}

        public function setUsername($username)
        {
            $this->username = $username;
            return $this;
        }
        
        public function getEmail(){return $this->email;}
        
        public function setEmail($email)
        {
            $regex = '/[a-zA-Z0-9_.+-]+@(student\.)?thomasmore\.be/';
            if(preg_match_all($regex, $email)){
                $this->email = $email;
                return $this;
            } else{
                throw new Exception("Please use your thomasmore account to register");
            }
        }

        public function getPassword(){return $this->password;}

        public function setPassword( $password )
        {
            if(strlen($password) < 6){
                throw new Exception("Passwords must be 6 characters or longer.");
            }

            $this->password = $password;
            return $this;
        }

        public function canLogin($password) {
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue(':email', $this -> email);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);
            return password_verify($password, $res["password"]);
        }

        public function register() {
            if(!$this->userExists()){
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
    }