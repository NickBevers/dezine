<?php 

    include_once(__DIR__ . "/helpers/Security.help.php");
    Security::onlyLoggedInUsers();
    include_once(__DIR__ . "/autoloader.php");

    $posts = Post::getAllPosts();


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
    <p>*inserts very pretty design of very clean homepage with epic IMD themed styling and more posts than this:*</p>
    <!-- <img width="50%" src="assets\faker_post.jpg" alt="empty post"> -->
    <?php foreach($posts as $post): ?>
        <div><?php echo $post["title"] ?></div>
        <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
    <?php endforeach; ?>
</body>
</html>