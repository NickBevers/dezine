<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Auth\Reset;
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Cleaner;

    if (!empty($_POST)) {
        if (Validate::isNotEmpty($_POST['email'])) {
            $emailId = $_POST['email'];
            // echo $emailId;
            try {
                $reset = new Reset();
                $reset->setEmail($emailId);
                $message = $reset->resetMail();
                $message = Cleaner::xss($message);
            } catch (Throwable $e) {
                $error = $e->getMessage();
                $error = Cleaner::xss($error);
            }
        }
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <title>Reset password</title>
</head>
   <body class="container">
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
      <main>         
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo Cleaner::xss($message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo Cleaner::xss($error); ?></div>
        <?php endif; ?>

        <form action="" method="post" class="form form--profile">
            <h2>Reset password</h2>
            <div class="form__field">
                <label for="email" class="form-label">Email address</label>
                <input type="text" name="email" placeholder="Email" class="form-control" id="email">
            </div>
            <div class="form__field">
                <button type="submit" name="reset-token" class="btn secondary__btn secondary__btn-signup">Reset</button>
            </div>          
        </form>
      </main>
      <?php include_once("./includes/footer.inc.php"); ?>
   </body>
</html>