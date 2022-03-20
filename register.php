<?php 
    
    include_once(__DIR__ . "/classes/DB.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");

    function userExists($email){
      $conn = DB::getInstance();
      $statement = $conn->prepare("select * from users where email = :email");
      $statement->bindValue(':email', $email);
      $statement->execute();
      $res = $statement->fetch();
      // var_dump($res);
      return $res;
    }

    if(!empty($_POST)){
      $email = $_POST["email"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $password_conf = $_POST["password_conf"];

      if(CheckEmpty::isNotEmpty($email) && CheckEmpty::isNotEmpty($username) && CheckEmpty::isNotEmpty($password) && CheckEmpty::isNotEmpty($password_conf)){
        if(strlen($password) >= 6 && $password === $password_conf){
          if(userExists($email) === false){
            // echo "TRUUEEEEEEEEEEEEEEE";
            $conn = DB::getInstance();
            $options = [
              'cost' => 15
            ];
            $password = password_hash($password, PASSWORD_DEFAULT, $options);
            $statement = $conn->prepare("insert into users (username, email, password) values (:username, :email, :password);");
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
          } else{
            echo "USEER EXISTS";
          }
        } else{
          echo "PASSWORDS DON'T MATCH";
        }
      } else{
        echo "PLEASE FILL IN ALL FIELDS";
      }
    }

?><!DOCTYPE html>
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

<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>

  <div class="mb-3">
    <label for="exampleInputUsername1" class="form-label">Username</label>
    <input type="text" name="username" class="form-control" id="exampleInputUsername1">
  </div>

  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    <div id="passwordHelp" class="form-text">Passwords must be at least 6 characters long</div>
  </div>

  <div class="mb-3">
    <label for="password_conf" class="form-label">Password confirmation</label>
    <input type="password" name="password_conf" class="form-control" id="password_conf">
    <div id="passwordHelp" class="form-text">Passwords must match password above</div>
  </div>
  
  <button type="submit" class="btn btn-primary">Sign me up</button>
</form>


</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>