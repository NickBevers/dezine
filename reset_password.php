<?php
    include_once("bootstrap.php");
    use \Helpers\Validate;
    use \Helpers\Security;
    use \Helpers\Cleaner;
    use Classes\Auth\Reset;
    

    if($_GET['key'] && $_GET['token']){    
        $email = $_GET['key'];
        $token = $_GET['token'];

        $reset = new Reset();
        $reset->setEmail($email);
        $reset->setToken($token);
        $returned = $reset->resetLink();
        // var_dump($returned);

        if (!empty($_POST)) {
          $new_password = $_POST["new_password"];
          $password_conf = $_POST["password_conf"];
          $email = $returned;
 
          if(Validate::isNotEmpty($new_password) && Validate::isNotEmpty($password_conf)){
             if($new_password === $password_conf){
                try {
                    Reset::resetPassword($email, $new_password);
                    $success = "Your password was successfully updated";
                } catch (Throwable $error) {
                    // if any errors are thrown in the class, they can be caught here
                    $error = $error->getMessage();
                }
             } else{
             $error = "The passwords don't match";
             }
           } else{
             $error = "Please fill in all fields of the form";
           }
      }
    }
?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
      <title>Reset Password</title>
      <link rel="stylesheet" href="./styles/style.css">
      <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
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

      <?php if ($returned): ?>
        <form action="" method="post" class="form form--register">
          <h2>Reset password</h2>
          <input type="hidden" name="email" value="<?php echo $returned;?>">
          <!-- <input type="hidden" name="reset_link_token" value="<//?php echo $tok;?>"> -->

          <div class="form__field">
              <label for="exampleInputPassword1" class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control" id="exampleInputPassword1">
              <div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
          </div>
          <div class="form__field">
              <label for="password_conf" class="form-label">Password confirmation</label>
              <input type="password" name="password_conf" class="form-control" id="password_conf">
              <div id="passwordHelp" class="form-text">Passwords must match password above</div>
          </div>
          
          <button type="submit" class="btn secondary__btn secondary__btn-signup">Reset password</button>
        </form>

        <?php elseif(isset($message)): ?>
          <h3><?php echo $message; ?></h3>
      <?php endif; ?>

    </main>
   </body>
</html>