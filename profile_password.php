<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Dezine\Helpers\Validate;
    use \Dezine\Helpers\Security;
    use \Dezine\Auth\User;

    Validate::start();

    if (!Security::isLoggedIn()) {header('Location: login.php');}

    if (!empty($_POST)) {
        $c_password = $_POST["c_password"];
        $new_password = $_POST["new_password"];
        $password_conf = $_POST["password_conf"];
        $email = $_SESSION['email'];

        if (Validate::isNotEmpty($c_password) && Validate::isNotEmpty($new_password) && Validate::isNotEmpty($password_conf)) {
            if ($new_password === $password_conf) {
                if ($c_password === $new_password) {
                    $error = "New password cannot be the same as the old password";
                } else {
                    try {
                        User::resetPassword($email, $c_password, $new_password);
                        $success = "Your password was successfully updated";
                    } catch (Throwable $error) {
                        // if any errors are thrown in the class, they can be caught here
                        $error = $error->getMessage();
                    }
                }
            } else {
                $error = "The passwords don't match";
            }
        } else {
            $error = "Please fill in all fields of the form";
        }
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dezine</title>
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
</head>
    <body class="container">
        <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
        
        <main>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="post" action="" class="form form--register">
            <h2>Change password</h2>
                <div class="form__field">
                    <label for="c_password" class="form-label">Current Password</label>
                    <input type="password" name="c_password" class="form-control">
                </div>

                <div class="form__field">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control">
                    <div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
                </div>

                <div class="form__field">
                    <label for="password_conf" class="form-label">Password confirmation</label>
                    <input type="password" name="password_conf" class="form-control" id="password_conf">
                    <div id="passwordHelp" class="form-text">Passwords must match password above</div>
                </div>
                
                <div class="form__field">
                    <button type="submit" class="btn secondary__btn secondary__btn-signup">Change password</button>
                </div>
            </form>
        </main>
    </body>    
</html>