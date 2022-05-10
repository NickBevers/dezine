<?php
  include_once(__DIR__ . "/autoloader.php");
  include_once(__DIR__ . "/helpers/CheckEmpty.help.php");

  if(!empty($_POST)) {
    if(CheckEmpty::isNotEmpty($_POST['email'])){          
        $emailId = $_POST['email'];

        // echo $emailId;
        try{
          $reset = new Reset();
          $reset->setEmail($emailId);
          $message = $reset->resetMail();
        } catch(Throwable $e){
          $error = $e->getMessage();
        }
        
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
    <title>Reset password</title>
</head>
   <body>
      <div class="container">
          <div class="card">
            <div class="card-header text-center">
              Reset password
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card-body">
              <form action="" method="post">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email</label>
                  <input type="email" name="email" class="form-control" id="email">
                </div>
                <input type="submit" name="reset-token" class="btn btn-primary">
              </form>
            </div>
          </div>
      </div>
 
   </body>
</html>