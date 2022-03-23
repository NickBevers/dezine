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
            $statement->bindValue(":email", $email);
            $statement->execute();
            $result = $statement->fetch();

            // var_dump($result , "result");

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
                $state->bindValue(":reset_token", $token);
                $state->bindValue(":exp_token", $expDate);
                $state->bindValue(":email", $email);
                $state->execute();
                
                // var_dump($state, " state");
                //link nog aanpassen
                $link = "<a href='localhost/dezine/reset_password.php?key=".$email."&token=".$token."'>Click To Reset password</a>";

                // $mail = new PHPMailer();
                // try{
                //     //SERVER SETTINGS
                //     $mail->SMTPDebug = 2;                      
                //     //Enable verbose debug output
                //     $mail->isSMTP();                                            
                //     //Send using SMTP
                //     $mail->Host = 'smtp.mailtrap.io';                    
                //     //Set the SMTP server to send through
                //     $mail->SMTPAuth = true;                                   
                //     //Enable SMTP authentication
                //     $mail->Username = '97938d8e151717';
                //     //SMTP username
                //     $mail->Password = 'bfc9fd61e3c82e';                  
                //     //SMTP password
                //     $mail->SMTPSecure = "tls";            
                //     //Enable implicit TLS encryption
                //     $mail->Port = 2525;                                    
                //     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                
                //     //Recipient
                //     $mail->setFrom('from@smtp.mailtrap.io', 'Mailer'); //nog aanpassen
                //     $mail->addAddress($email);  //moet er een naam bij?

                //     //Content
                //     $mail->isHTML(true);                                  //Set email format to HTML
                //     $mail->Subject = 'Reset your Dezine Password!';
                //     $mail->Body    = `<h1>It seems like you forgot your password, be sure to log back in again!</h1> \n <a href="$link">Click here</a>`;

                //     $mail->send();

                //     var_dump($mail, " mail");
                //     $message = "Mail has been send";
                //     return $message;
                // } catch(Exception $e){
                //     $error = $mail->ErrorInfo;
                //     return $error;
                // }
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = '97938d8e151717';                     //SMTP username
                    $mail->Password   = 'bfc9fd61e3c82e';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('from@example.com', 'Mailer');
                    $mail->addAddress($email, 'Joe User');     //Add a recipient
                    
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Here is the subject';
                    $mail->Body    = $link;

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
            $em = $this->email;
            $tok = $this->token;
            $conn = DB::getInstance();
            $query = $conn->prepare("select * from users where reset_token = :reset_token and email = :email");
            $query->bindValue(":reset_token", $tok);
            $query->bindValue(":email", $em);
            $query->execute();
            // var_dump($query->fetch());

            $curDate = date("Y-m-d H:i:s");            
            // var_dump($curDate);

            // echo "yes";

            if ($query->rowCount() > 0) {
                // echo "help";
                $row = $query->fetch();
                // var_dump($row);
                $expDate = $row['exp_token'];
                // var_dump($expDate, "exp");
                if ($expDate >= $curDate){
                    // echo "no";
                    // var_dump($em);              
                    return $em;
                }
                else{                    
                    // echo "ok";
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
                $statement = $conn->prepare("update users set password= :password where email= :email");
                $statement->bindValue(':password', $n_password);
                $statement->bindValue(':email', $email);
                $statement->execute();
                // $result = $statement->fetch();
                // var_dump($result);
            }
        }
    }