<?php
    include_once("bootstrap.php");
    use \Helpers\Validate;
    use \Helpers\Security;
    use Classes\Content\Post;
    use Classes\Actions\Comment;
    use Classes\Auth\User;    
    use phpDocumentor\Reflection\Location;

    Validate::start();
    
    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    if (isset($_GET["pid"])) {
        $post = Post::getPostbyPostId($_GET["pid"]);
    } else {
        header('Location: home.php');
    }
    
    $comments = Comment::getCommentsByPostId($_GET["pid"]);
    
    $visitor = Post::getViewsbyId($_SESSION["id"], $_GET["pid"]);

    if($_SESSION["id"] !== $post["user_id"]){
        if($visitor === false){
            Post::addViewbyPost($_GET["pid"], $_SESSION["id"]);
        }
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <title><?php echo $post["title"] ?></title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <div class="post--single">                    
        <div class="post--single__user">
            <?php $user = User::getUserbyId($post["user_id"]); ?>
            <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>" class="posts__user__img">
            <a href="profile.php?id=<?php echo $post["user_id"]; ?>">
                <h3><?php echo $user["username"] ?></h3>         
            </a>
            <?php if($_SESSION["id"] === $post["user_id"]): ?>
                <div class="views">
                    <img src="./assets/eye_icon.svg" alt="eye icon for views count">
                    <span><?php echo Post::getViewsbyPost($_GET["pid"]); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="post post--single__content">
            <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
            <div class="post__info">
                <h3 class="post__title"><?php echo $post["title"] ?></h3>
                <?php if (isset($_SESSION["id"])): ?>
                    <p><?php echo $post["description"] ?></p>
                    <?php $tags = json_decode($post["tags"]); ?>
                    <div class="post__info__tags">
                        <?php foreach ($tags as $t): ?>
                            <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>  
            </div>
        </div>
        <?php if(intval(User::checkban($_SESSION["id"])) === 0): ?>
            <div class="post__comment">
                <div class="post__comment__form">
                    <?php $user = User::getUserbyId($_SESSION['id']); ?>
                    <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
                    <input type="text" placeholder="What are your thoughts on this project?" class="post__comment__form__input">
                    <a class="post__comment__form__btn" 
                        data-pfplink="<?php echo $user["profile_image"]; ?>" 
                        data-postid="<?php echo $post["id"];?>"
                        data-uid="<?php echo $_SESSION["id"];?>" 
                        data-username="<?php echo $user["username"]; ?>"
                    >
                        Add
                    </a>
                </div>
                <ul class="post__comment__list">
                    <?php foreach($comments as $comment): ?>
                        <li>
                            <div class="post__comment--left">
                                <a href="./profile.php?id=<?php echo $comment["user_id"]; ?>">
                                    <img src="<?php echo $comment["profile_image"]; ?>" alt="<?php echo $comment["username"]; ?>">
                                </a>
                            </div>

                            <div class="comment--right">
                                <a href="./profile.php?id=<?php echo $comment["user_id"]; ?>">
                                    <?php echo $comment['username']; ?>
                                </a>
                                <p><?php echo $comment['comment']; ?></p>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <script src="javascript\comments.js"></script>
</body>
</html>