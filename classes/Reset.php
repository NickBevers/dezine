<?php

include_once(__DIR__ . "/DB.php");
include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    class Reset{
        private $email;
        private $token;

        public function setEmail($email){
            $email = Cleaner::cleanInput($email);
            $this->email = $email;
            return $this;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setToken($token){
            $token = Cleaner::cleanInput($token);
            $this->token = $token;
            return $this;
        }

        public function getToken(){
            return $this->token;
        }

        const PASSWORD_MIN_LENGTH = 6;

        public function resetMail(){
            $email = $this->email;
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue("email", $email);
            $statement->execute();
            $result = $statement->fetch();

            if ($result) {
                $token = md5($email).rand(10, 9999);
                $expFormat = mktime(
                    date("H"),
                    date("i"),
                    date("s"),
                    date("m"),
                    date("d")+1,
                    date("Y")
                );

                $expDate = date("Y-m-d H:i:s", $expFormat);

                $state = $conn->prepare("update users set reset_token = :reset_token, exp_token = :exp_token where email = :email");
                $state->bindValue("reset_token", $token);
                $state->bindValue("exp_token", $expDate);
                $state->bindValue("email", $email);
        
                //link nog aanpassen
                $link = "<a href='www.yourwebsite.com/reset-password.php?key=".$email."&token=".$token."'>Click To Reset password</a>";

                $to = $email;
                $subject = `Reset your Dezine Password!`;
                $message = `It seems like you forgot your password, be sure to log back in again! \n Click here $link`;

                $resetMail = mail($to, $subject, $message);
                return $resetMail;
            }
        }

        public function resetLink(){
            $em = $this->email;
            $tok = $this->token;
            $conn = DB::getInstance();
            $query = $conn->prepare("select * from users where reset_token = :reset_token and email = :email");
            $query->bindValue("reset_token", $tok);
            $query->bindValue("email", $em);
            $query->execute();

            $curDate = date("Y-m-d H:i:s");

            if ($query->rowCount() > 0) {
                $row = $query->fetch();
                if ($row['exp_date'] >= $curDate){
                    return $em && $tok;
                }
            } else{
                $message = "This forget password link has been expired";
                return $message;
            }
        }

        public static function resetPassword($email, $new_password){
            if(strlen($new_password) < self::PASSWORD_MIN_LENGTH){
                throw new Exception("Passwords must be " . self::PASSWORD_MIN_LENGTH . " characters or longer.");
            } else{
                $options = [
                'cost' => 15
                ];
                $n_password = password_hash($new_password, PASSWORD_DEFAULT, $options);
                $conn = DB::getInstance();
                $statement = $conn->prepare("update users set password= :password where email= :email");
                $statement->bindValue(':password', $n_password);
                $statement->bindValue(':email', $email);
                $statement->execute();
                // $result = $statement->fetch();
                // var_dump($result);
            }
        }
    }