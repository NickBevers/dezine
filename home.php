<?php 
	include_once("./includes/loggedInCheck.inc.php");
    include_once(__DIR__ . "/helpers/Security.help.php");

    include_once(__DIR__ . "/autoloader.php");

    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    
    if (isset($_GET["page"]) && $_GET["page"] > 1) { 
        $pageNum  = $_GET["page"];
        $posts = Post::getSomePosts($pageNum*$postsPerPage, $postsPerPage);

    } else {
        $pageNum  = 1;
        $posts = Post::getSomePosts(0, $postsPerPage);
    };
    //temporary getAllPosts
    // $posts = Post::getAllPosts();

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
    <?php if(isset($_SESSION['email'])): ?>
        <h3> Welcome <?php echo $_SESSION['email'] ?></h3>
    <?php endif; ?>
    <p>*inserts very pretty design of very clean homepage with epic IMD themed styling and more posts than this:*</p>
    <!-- <img width="50%" src="assets\faker_post.jpg" alt="empty post"> -->
    <?php foreach($posts as $post): ?>
        <div><?php echo $post["title"] ?></div>
        <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
        <?php if(isset($_SESSION["id"])): ?>
            <div><?php echo $post["description"] ?></div>
            <div><?php echo $post["tags"] ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if($postCount > $postsPerPage): ?>
        <?php if($pageNum > 1): ?>
            <a href="home.php?page=<?php echo $pageNum-1 ?>" class="next_page">Previous page</a>
        <?php endif; ?>
        <a href="home.php?page=<?php echo $pageNum+1 ?>" class="next_page">Next page</a>
    <?php endif; ?>
</body>
</html>