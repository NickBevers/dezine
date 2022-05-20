<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Security;
    use Dezine\Helpers\Cleaner;
    use Dezine\Auth\User;
    use Dezine\Auth\Link;

    if (Security::isLoggedIn()) {
        header('Location: home.php');
    }

    if (!empty($_GET["token"])) {
        $token = Cleaner::cleanInput($_GET["token"]);
        if (!Link::checkLink($token)) {
            header('Location: register.php');
            $error="This link was not valid, please try again.";
        }
        $_SESSION["token"] = $token;
        // setcookie("token", $token, time() + 3600);
    }

    if (!empty($_POST)) {
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password_conf = $_POST["password_conf"];
      
        if ($password === $password_conf) {
            try {
                $default_image = "assets/default_profile_image.png";          
                $user = new User();
            
                // use setters to fill in data for this user
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setProfileImage($default_image);

                
                if (isset($_SESSION["token"])) {
                    $id = $user->register($_SESSION["token"]);
                    Link::removeLink($_SESSION["token"]);
                } else {
                    $id = $user->register();
                }
                session_start();
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['id'] = $id;
                header("Location: home.php");
            } catch (Throwable $error) {
                // if any errors are thrown in the class, they can be caught here
                $error = $error->getMessage();
            }
        } else {
            $error = "The passwords don't match";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/style.css">
  <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
  <title>Dezine</title>
</head>

<body class="container">
  <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>

  <main>
    <?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> class="form form--register">
    <h2>Register</h2>
      <div class="form__field">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" placeholder="Email" class="form-control register--email" id="exampleInputEmail1"
          aria-describedby="emailHelp" required>
        <div class="message message--email"></div>
      </div>

      <div class="form__field">
        <label for="exampleInputUsername1" class="form-label">Username</label>
        <input type="text" name="username" placeholder="Username" class="form-control register--username" id="exampleInputUsername1" required>
        <div class="message message--username"></div>
      </div>

      <div class="form__field">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" placeholder="Password" class="form-control" id="exampleInputPassword1" required>
        <div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
      </div>

      <div class="form__field">
        <label for="password_conf" class="form-label">Password confirmation</label>
        <input type="password" name="password_conf" placeholder="Password confirmation" class="form-control" id="password_conf" required>
        <div id="passwordHelp" class="form-text">Passwords must match password above</div>
      </div>

      <button type="submit" class="btn secondary__btn secondary__btn-signup">Sign me up</button>
    </form>
  </main>

  <?php include_once("./includes/footer.inc.php"); ?>

  <script src="./javascript/register.js"></script>
</body>

</html>