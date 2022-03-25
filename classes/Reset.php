<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    include_once(__DIR__ . "/DB.php");
    include_once(__DIR__ . "/../helpers/Cleaner.help.php");

    
    class Reset{
        private $email;
        private $token;
        const PASSWORD_MIN_LENGTH = 6;

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


        public function resetMail(){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue(":email", $this->email);
            $statement->execute();
            $result = $statement->fetch();

            // var_dump($result , "result");

            if ($result) {
                $token = md5($this->email).rand(10, 9999);
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
                $state->bindValue(":reset_token", $token);
                $state->bindValue(":exp_token", $expDate);
                $state->bindValue(":email", $this->email);
                $state->execute();
                
                //link nog aanpassen

                $link = "<a href='localhost/dezine/reset_password.php?key=".$this->email."&token=".$token."'>Click To Reset password</a>";

                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'dezine.php@gmail.com';
                    $mail->Password = 'NHZ^%Ktr3QgK$BMCPzq8BwiW';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('reset@dezine.be', 'Dezine');
                    $mail->addAddress($this->email, '');
                    
                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password reset link';
                    $mail->Body = $link;

                    $mail->send();
                    $message = 'Message has been sent';
                    return $message;
                } catch (Exception $e) {
                    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    return $message;
                }
            }
        }

        public function resetLink(){
            $conn = DB::getInstance();
            $query = $conn->prepare("select * from users where reset_token = :reset_token and email = :email");
            $query->bindValue(":reset_token", $this->token);
            $query->bindValue(":email", $this->email);
            $query->execute();
            $curDate = date("Y-m-d H:i:s");

            if ($query->rowCount() > 0) {
                $row = $query->fetch();
                $expDate = $row['exp_token'];
                if ($expDate >= $curDate){            
                    return $this->email;
                }
                else{
                    $message = "This forget password link has been expired";
                    return $message;
                }
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
                $statement = $conn->prepare("update users set password= :password, reset_token= :reset_token, exp_token= :exp_token where email= :email");
                $statement->bindValue(':password', $n_password);
                $statement->bindValue(":reset_token", NULL);
                $statement->bindValue(":exp_token", NULL);
                $statement->bindValue(':email', $email);
                $statement->execute();

                // $result = $statement->fetch();
            }
        }
    }