<?php 
    include_once(__DIR__ . "/autoloader.php");

    include_once("./helpers/Security.help.php");
	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    //var_dump($_SESSION);
    
    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
   
    if(!empty($_GET["search"])){
        $search_term = Cleaner::cleanInput($_GET["search"]);
        if (isset($_GET["page"]) && $_GET["page"] > 1) { 
            $pageNum  = $_GET["page"];
            $posts = Post::getSearchPosts($search_term, $pageNum*$postsPerPage, $postsPerPage);

        } else {
            $pageNum  = 1;
            $posts = Post::getSearchPosts($search_term, 0, $postsPerPage); 

            // weet niet of dit de juiste manier is voor melding waneer er geen posts verzonden zijn

            
           
        };
    } else{
        if (isset($_GET["page"]) && $_GET["page"] > 1) { 
            $pageNum  = $_GET["page"];
            $posts = Post::getSomePosts($pageNum*$postsPerPage, $postsPerPage);
    
        } else {
            $pageNum  = 1;
            $posts = Post::getSomePosts(0, $postsPerPage);
        };
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Dezine Home</title>
</head>
<body>
<?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    HOME
    <?php if(isset($_SESSION['email'])): ?>
        <h3> Welcome <?php echo $_SESSION['email'] ?></h3>
    <?php endif; ?>

    <section class="search_box">

    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search here..." required="required" />
        <input type="submit" value="submit">
        <?php if(!empty($_GET["search"])): ?>
            <a href="home.php">X</a>
        <?php endif; ?>

    </form>

    </section>

    <section class="posts">

        <?php if (empty($posts)): ?>

            <img src="assets/noposts.png ">

        <?php endif; ?> 
        <?php foreach($posts as $post): ?>
            <div>                    
                <div class="posts__user">
                    <?php $user = User::getUserbyId($post["user_id"]); ?>
                    <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
                    <a href="profile.php?id=<?php echo $post["user_id"]; ?>">
                        <h3><?php echo $user["username"] ?></h3>         
                    </a>
                </div>
            
                <div class="post">
                    <a class="post__link" href="detailsPost.php?pid=<?php echo $post["id"];?>">
                        <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
                    </a>
                    
                    <div class="post__info">
                    <a class="post__link" href="detailsPost.php?pid=<?php echo $post["id"];?>">
                        <h3 class="post__title"><?php echo $post["title"] ?></h3>
                    </a>
                    <?php if(isset($_SESSION["id"])): ?>
                        <p><?php echo $post["description"] ?></p>
                        <?php $tags = json_decode($post["tags"]); ?>
                        <div class="post__info__tags">
                            <?php foreach($tags as $t): ?>
                                <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>  
                    </div>
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