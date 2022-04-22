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
    <link rel="stylesheet" href="./css/style.css">
    <title>Profile</title>
</head>
<body>
    <section class="profile__info">
        <div class="profile__info__img">
            <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
        </div>
        <div class="profile__info__details">
            <h1><?php echo $user["username"]; ?></h1>
            <h4><?php echo $user["education"]; ?></h4>
            <p><?php echo $user["bio"]; ?></p>
            <div>
                <a href="<?php echo $user["website"]; ?>"><?php echo $user["website"]; ?></a>
                <a href="<?php echo $user["instagram"]; ?>"><?php echo $user["instagram"]; ?></a>
                <a href="<?php echo $user["github"]; ?>"><?php echo $user["github"]; ?></a>
                <a href="<?php echo $user["linkedin"]; ?>"><?php echo $user["linkedin"]; ?></a>
            </div>
        </div>    
    </section>
    
    <section class="posts">
    <?php foreach($posts as $post): ?>
        <div class="post">
            <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
            <div class="post__info">
                <h3><?php echo $post["title"] ?></h3>
                <?php if(isset($_SESSION["id"])): ?>
                    <p><?php echo $post["description"] ?></p>
                    <?php $tags = $post["tags"]; 
                    $tags = json_decode($tags);
                    $i=0;
                    ?>
                    <div class="post__info__tags">
                        <?php foreach($tags as $t): ?>
                            <p><?php echo "#"; echo $tags[$i]; echo "&nbsp"; $i++; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?> 
            </div>
            
        </div>              
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