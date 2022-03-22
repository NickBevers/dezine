<?php

    include_once(__DIR__ . "/classes/Reset.php");

    if($_GET['key'] && $_GET['token']){    
        $email = $_GET['key'];
        $token = $_GET['token'];

        Reset::resetLink($email, $token);
    }
?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
      <title>Reset Password</title>
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   </head>
   <body>

      <div class="container">
          <div class="card">
            <div class="card-header text-center">
              Reset Password
            </div>
            <div class="card-body">
            <?php if (isset($em, $tok)): ?>
              <form action="update-forget-password.php" method="post">
                <input type="hidden" name="email" value="<?php echo $em;?>">
                <input type="hidden" name="reset_link_token" value="<?php echo $tok;?>">
                <div class="form-group">
                  <label for="exampleInputEmail1">Password</label>
                  <input type="password" name='password' class="form-control">
                </div>                

                <div class="form-group">
                  <label for="exampleInputEmail1">Confirm Password</label>
                  <input type="password" name='cpassword' class="form-control">
                </div>
                <input type="submit" name="new-password" class="btn btn-primary">
              </form>
              <?php elseif(isset($message)): ?>
                <h3><?php echo $message; ?></h3>
            <?php endif; ?>

            </div>
          </div>
      </div>

   </body>
</html>