<?php 
	include_once("./includes/loggedInCheck.inc.php");
    include_once(__DIR__ . "/helpers/Security.help.php");

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dezine Home</title>
</head>
<body>
    HOME
    <h3> Welcome <?php echo $_SESSION['email'] ?></h3>
    <h3><?php echo $_SESSION['id'] ?></h3>
    <p>*inserts very pretty design of very clean homepage with epic IMD themed styling and more posts than this:*</p>
    <img width="50%" src="assets\faker_post.jpg" alt="empty post">
</body>
</html>