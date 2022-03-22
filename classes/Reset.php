<?php

include_once(__DIR__ . "/DB.php");

    class Reset{

        public function resetMail($emailId){
            $conn = DB::getInstance();
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindValue("email", $emailId);
            $statement->execute();
            $result = $statement->fetch();

            if ($result) {
                $token = md5($emailId).rand(10, 9999);
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
                $state->bindValue("email", $emailId);
        
                //link nog aanpassen
                $link = "<a href='www.yourwebsite.com/reset-password.php?key=".$emailId."&token=".$token."'>Click To Reset password</a>";

                $to = $emailId;
                $subject = `Reset your Dezine Password!`;
                $message = `It seems like you forgot your password, be sure to log back in again! \n Click here $link`;

                $resetMail = mail($to, $subject, $message);
                return $resetMail;
            }
        }

        public static function resetLink($email, $token){
            $em = $email;
            $tok = $token;
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
    }