<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Security;
    use Dezine\Auth\User;
	use Dezine\Helpers\Validate;

    if (Security::isLoggedIn()) {
        header('Location: home.php');
    }
    
    if (!empty($_POST)) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        try {
            $user = new User;
            $user->setEmail($email);
            $user->setPassword($password);
            $usr = $user->canLogin();
            if ($usr) {
				Validate::start();
                $_SESSION['email'] = $user->getEmail();
                $_SESSION['id'] = $usr["id"];
                header("Location: home.php");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./styles/style.css">
	<link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
	<title>Login</title>
</head>

<body class="container">
	<?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
	<main>
		<?php if (isset($error)) : ?>
		<div class="alert alert-danger"><?php echo $error; ?></div>
		<?php endif; ?>

		<form method="post" action class="form form--profile">
			<h2>Login</h2>
			<div class="form__field">
				<label for="exampleInputEmail1" class="form-label">Email address</label>
				<input name="email" placeholder="Email" type="email" class="form-control register--email" required />
				<div id="passwordHelp" class="form-text">Please login with your Thomas More email</div>
			</div>
			<div class="form__field">
				<label for="exampleInputPassword1" class="form-label">Password</label>
				<input name="password" placeholder="Password" type="password" class="form-control" required />
				<div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
			</div>
			<div class="form__submit form__field">
				<button type="submit" class="btn secondary__btn secondary__btn-signup">Login</button>
				<a href="reset.php" class="btn secondary__btn-reverse secondary__btn-signup">Forgot password?</a>
			</div>
		</form>
	</main>
	<?php include_once("./includes/footer.inc.php"); ?>
</body>

</html>