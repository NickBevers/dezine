<?php
    include_once(__DIR__ . "/classes/DB.php");
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");
    include_once(__DIR__ . "/classes/User.php");


    Security::onlyLoggedInUsers();

    if (!empty($_POST)) {
        $c_password = $_POST["c_password"];
        $new_password = $_POST["new_password"];
        $password_conf = $_POST["password_conf"];
        $email = $_SESSION['email'];

        if(CheckEmpty::isNotEmpty($c_password) && CheckEmpty::isNotEmpty($new_password) && CheckEmpty::isNotEmpty($password_conf)){
            if($new_password === $password_conf){
                if ($c_password === $new_password) {
                    $error = "New password cannot be the same as the old password";
                } else{
                    try {
                        User::resetPassword($email, $c_password, $new_password);
                        $success = "Your password was successfully updated";
                    } catch (Throwable $error) {
                        // if any errors are thrown in the class, they can be caught here
                        $error = $error->getMessage();
                    }
                }
            } else{
                $error = "The passwords don't match";
            }
        } else{
            $error = "Please fill in all fields of the form";
        }
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dezine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
    <body class="container">
        <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
        
        <main>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label">Current Password</label>
                    <input type="password" name="c_password" class="form-control" id="exampleInputUsername1">
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" id="exampleInputPassword1">
                    <div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
                </div>

                <div class="mb-3">
                    <label for="password_conf" class="form-label">Password confirmation</label>
                    <input type="password" name="password_conf" class="form-control" id="password_conf">
                    <div id="passwordHelp" class="form-text">Passwords must match password above</div>
                </div>
                
                <button type="submit" class="btn btn-primary">Reset password</button>
            </form>

            <a href="./userDelete.php" class="btn btn-danger mt-3">Delete account</a>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>