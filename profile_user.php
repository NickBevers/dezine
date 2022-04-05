<?php
	include_once("./includes/loggedInCheck.inc.php");
    
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");
    include_once(__DIR__ . "/autoloader.php");

    $email = $_SESSION['email'];
    $user = new User();
    $users = $user->getUser($email);

    if (!empty($_POST)) {

        $username = $_POST['username'];
        $education = $_POST['education'];
        $bio = $_POST['bio'];
       

    
        
        try{
           
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setBio($bio);
            $user->setEducation($education);

            $user->updateUser();
            
            $success = "Your password was successfully updated";


        }
        catch (Throwable $error) {
            // if any errors are thrown in the class, they can be caught here
            $error = $error->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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

        <h4>Update Profile</h4>
        <br>



        <form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>



            <div class="mb-3">
                <label for="username" class="form-label">username</label>
                <input type="username" name="username" class="form-control" id="username"
                    value="<?php echo $users["username"]; ?>">
            </div>

            <div class="mb-3">
                <label for="education" class="form-label">education</label>
                <input type="education" name="education" class="form-control" id="education"
                    value="<?php echo $users["education"]; ?>">
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">bio</label>
                <textarea type="bio" name="bio" class="form-control" id="bio" cols="60"
                    row="30"><?php echo $users["bio"]; ?></textarea>
            </div>


            <button type="submit" class="btn btn-primary">Update</button>

        </form>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
</body>

</html>