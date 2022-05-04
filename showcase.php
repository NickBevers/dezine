<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");

    if(!Security::isLoggedIn()) { header('Location: login.php');}
    
    $uid = $_SESSION["id"]; 
    $id = $_GET["id"];
    if(empty($id)){
        if(empty($uid)){
            header('Location: home.php');
        }else{
            $profileUser = Cleaner::cleanInput($uid);
            header("Location: showcase.php?id=$uid");
        }        
    } else{
        $profileUser = Cleaner::cleanInput($id);
    }  

    $user = User::getUserbyId($profileUser);
    if(empty($user)){ header('Location: home.php');}
    
    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    $post = new Post();
    
    if (isset($_GET["page"]) && $_GET["page"] > 1) { 
        $pageNum  = $_GET["page"];
        $posts = $post->getPostbyId($profileUser, $pageNum*$postsPerPage, $postsPerPage);

    } else {
        $pageNum  = 1;
        $posts = $post->getPostbyId($profileUser, 0, $postsPerPage);
    }    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Showcase</title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    
    <h1 class="showcase__title"><?php echo $user["username"]; ?>'s Showcase</h1>

    <section>
        <?php if(Showcase::userHasShowcase($id) === false && $uid === $id): ?>
            <div class="showcase__empty">
                <h2 class="showcase__title-h2">Your showcase is still empty!</h2>
                <div class="showcase__empty-message">
                    <p>Add posts to your showcase by clicking on the hearts!</p>   
                    <img src="./assets/hearts_icon.svg" alt="hearts icon showcase">
                </div>
            </div>
        <?php else: ?>
            <div class="posts">
                <?php foreach($posts as $post): ?>
                    <?php if(Showcase::checkShowcase($post["id"], $id)): ?>
                        <div class="post post__showcase">
                            <div class="post__img">
                                    <?php if($uid === $post["user_id"]): ?>
                                        <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts hidden" data-id="<?php echo $post["id"]; ?>">
                                        <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase" class="heartsfull" data-id="<?php echo $post["id"]; ?>">
                                    <?php endif; ?>
                                <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
                            </div>
                            <div class="post__info">
                                <h4><?php echo $post["title"] ?></h4>
                                <?php if(isset($uid)): ?>
                                    <p><?php echo $post["description"] ?></p>
                                    <?php $tags = json_decode($post["tags"]); ?>
                                    <div class="post__info__tags">
                                        <?php foreach($tags as $t): ?>
                                            <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if($_SESSION["id"] == $_GET["id"]): ?>
                                        <div class="post__actions">
                                            <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                                <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                                            </a>
                                            
                                            <a href="edit_post.php?pid=<?php echo($post['id']); ?>&uid=<?php echo($_SESSION["id"]); ?>">
                                                <img class="edit_icon" src="./assets/icon_edit.svg" alt="edit pencil :sparkle:">
                                            </a>
                                        </div>                        
                                    <?php endif; ?> 
                                <?php endif; ?> 
                            </div>
                        </div>       
                    <?php endif; ?> 
                <?php endforeach; ?>
            </div>  
        <?php endif; ?>  
    </section>

    <?php if($postCount > $postsPerPage): ?>
        <?php if($pageNum > 1): ?>
            <a href="home.php?page=<?php echo $pageNum-1 ?>" class="next_page">Previous page</a>
        <?php endif; ?>
        <a href="home.php?page=<?php echo $pageNum+1 ?>" class="next_page">Next page</a>
    <?php endif; ?>

    <script src="./javascript/showcase.js"></script>
</body>
</html>