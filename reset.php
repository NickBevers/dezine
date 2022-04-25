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
    <title>Reset password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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