<?php
    include_once(__DIR__ . "/autoloader.php");

    include_once("./helpers/Security.help.php");
	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    $profileUser = $_GET["id"];
    $user = User::getUserbyId($profileUser);

    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    $post = new Post();
    
    if (isset($_GET["page"]) && $_GET["page"] > 1) { 
        $pageNum  = $_GET["page"];
        $posts = $post->getPostbyId($profileUser, $pageNum*$postsPerPage, $postsPerPage);

    } else {
        $pageNum  = 1;
        $posts = $post->getPostbyId($profileUser, 0, $postsPerPage);
    };
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <section>
        <div>
            <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
        </div>
        <div>
            <h1><?php echo $user["username"]; ?></h1>
            <h4><?php echo $user["education"]; ?></h4>
            <p><?php echo $user["bio"]; ?></p>
            <a href="<?php echo $user["website"]; ?>"><?php echo $user["website"]; ?></a>
            <a href="<?php echo $user["instagram"]; ?>"><?php echo $user["instagram"]; ?></a>
            <a href="<?php echo $user["github"]; ?>"><?php echo $user["github"]; ?></a>
            <a href="<?php echo $user["linkedin"]; ?>"><?php echo $user["linkedin"]; ?></a>
        </div>    
    </section>
    
    <section>
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
    </section>
</body>
</html>