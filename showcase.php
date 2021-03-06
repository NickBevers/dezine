<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Dezine\Helpers\Validate;
    use \Dezine\Helpers\Security;
    use \Dezine\Helpers\Cleaner;
    use \Dezine\Auth\User;
    use \Dezine\Content\Post;
    use \Dezine\Content\Showcase;
 
    Validate::start();

    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }
    
    $uid = Cleaner::cleanInput($_SESSION["id"]);
    $id = Cleaner::xss(Cleaner::cleanInput($_GET["id"]));
    if (empty($id)) {
        if (empty($uid)) {
            header('Location: home.php');
        } else {
            $profileUser = $uid;
            header("Location: showcase.php?id=$uid");
        }
    } else {
        $profileUser = $id;
    }

    $user = Cleaner::xss(User::getUserbyId($profileUser));
    if (empty($user)) {
        header('Location: home.php');
    }
    $posts = Post::getPostbyId($profileUser, 0, 100);
    $posts = Cleaner::xss($posts);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Showcase</title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <section class="profile__info">
        <div class="profile__info__img">
            <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
        </div>
        <div class="profile__info__block">
            <div class="profile__info__details">
                <div class="profile__info__details__username">
                    <h1><?php echo $user["username"]; ?></h1>
                    <img src="./assets/hearts_full_icon.svg" id="profile__verified" alt="hearts icon showcase">
                </div>
                <h4><?php echo $user["education"]; ?></h4>
                <p><?php echo $user["bio"]; ?></p>
                <div class="profile__info-share">
                    <a href="mailto:?subject=Check out this IMD showcase!&amp;body=Check out this IMD showcase https://www.weared-zine.be/showcase.php?id=<?php echo Cleaner::cleanInput($_GET["id"]); ?>"
                        title="Share by Email" class="btn primary__btn">
                            Share by mail
                    </a>
                    <button value="https://weared-zine.be/showcase.php?id=<?php echo $id; ?>" class="btn primary__btn share__link">Share by link</button>
                </div>
            </div>    
            <div class="profile__info-socials">
                <?php if(!empty($user["linkedin"])): ?>
                    <a href="<?php echo $user["linkedin"]; ?>" target="_blank"><img src="./assets/linkedin_icon.svg" alt="linkedin icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["website"])): ?>
                    <a href="<?php echo $user["website"]; ?>" target="_blank"><img src="./assets/web_small_icon.svg" alt="website icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["instagram"])): ?>
                    <a href="<?php echo $user["instagram"]; ?>" target="_blank"><img src="./assets/insta_small_icon.svg" alt="instagram icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["github"])): ?>
                    <a href="<?php echo $user["github"]; ?>" target="_blank"><img src="./assets/github_icon.svg" alt="github icon"></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section>
        <?php if (Showcase::userHasShowcase($id) === false && $uid === $id): ?>
            <div class="showcase__empty">
                <h2 class="showcase__title-h2">Your showcase is still empty!</h2>
                <div class="showcase__empty-message">
                    <p>Add posts to your showcase by clicking on the hearts!</p>   
                    <img src="./assets/hearts_full_icon.svg" alt="hearts icon showcase">
                </div>
            </div>
        <?php else: ?>
            <div class="posts">
                <?php foreach ($posts as $post): ?>
                    <?php if (Showcase::checkShowcase($post["id"], $id)): ?>
                        <div class="post post__showcase">
                            <div class="post__img">
                                    <?php if ($uid === $post["user_id"]): ?>
                                        <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts hidden" data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                                        <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase" class="heartsfull" data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                                    <?php endif; ?>
                                <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
                            </div>
                            <div class="post__info">
                                <h4><?php echo $post["title"] ?></h4>
                                <?php if (isset($uid)): ?>
                                    <p><?php echo $post["description"] ?></p>
                                    <?php $tags = json_decode($post["tags"]); ?>
                                    <div class="post__info__tags">
                                        <?php foreach ($tags as $t): ?>
                                            <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if ($uid == $id): ?>
                                        <div class="post__actions post__actions-showcase"> 
                                            <div class="post__actions-edit">                                          
                                                <a href="edit_post.php?pid=<?php echo($post['id']); ?>&uid=<?php echo($uid); ?>">
                                                    <img class="edit_icon" src="./assets/icon_edit.svg" alt="edit pencil :sparkle:">
                                                </a>
                                                <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                                    <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                                                </a>
                                            </div>
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
    <script src="./javascript/showcase.js"></script>
</body>
</html>