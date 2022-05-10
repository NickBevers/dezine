<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");
    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }
    

    if (empty($_GET["id"])) {
        if (empty($_SESSION["id"])) {
            header('Location: home.php');
        } else {
            $id = $_SESSION["id"];
            $profileUser = Cleaner::cleanInput($_SESSION["id"]);
            header("Location: profile.php?id=$id");
        }
    } else {
        $profileUser = Cleaner::cleanInput($_GET["id"]);
    }

    $user = User::getUserbyId($profileUser);
    if (empty($user)) {
        header('Location: home.php');
    }

    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    
    if (isset($_GET["page"]) && $_GET["page"] > 1) {
        $pageNum  = $_GET["page"];
        $posts = Post::getPostbyId($profileUser, $pageNum*$postsPerPage, $postsPerPage);
    } else {
        $pageNum  = 1;
        $posts = Post::getPostbyId($profileUser, 0, $postsPerPage);
    }

    $uid = Cleaner::cleanInput($_SESSION["id"]);

    $role = $user["user_role"];

    if(isset($_POST["moderator"])){
        if($_POST["moderator"] === "assign"){
            $role = "moderator";
            User::UpdateUserRole($role, $user["id"]);
        } else {
            $role = "user";
            User::UpdateUserRole($role, $user["id"]);
        }
        header("Refresh:0");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title><?php echo $user["username"]; ?></title>
</head>
<body>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="error">
            <p><?php echo($_SESSION['flash_error']); ?></p>
        </div>
    
    <?php
         unset($_SESSION['flash_error']);
        endif;
     ?>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <section class="profile__info">
        <div class="profile__info__image">
            <img src="<?php echo $user["profile_image"]; ?>" class="profile__info__img" alt="profile image <?php echo $user["username"]; ?>">
        </div>

        
        <div class="profile__info__details">
            <div class="profile__info__details__username">
                <h1><?php echo $user["username"]; ?></h1>
                <?php if($user["user_role"] !== "user" && User::checkUserRole($uid) !== "user"): ?>
                    <img src="assets\icon_check.svg" class="profile__info__details__verified" alt="verified icon">    
                <?php endif; ?> 
                <?php if(intval($user["id"]) !== intval($uid) && User::checkUserRole($uid) === "admin"): ?> 
                    <form action="#" method="post">
                        <?php if($user["user_role"] === "user"): ?>
                            <button name="moderator" value="assign" type="submit">Make moderator</button>
                        <?php endif; ?>
                        <?php if($user["user_role"] !== "user"): ?>
                            <button name="moderator" type="delete">Delete moderator role</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
            <h4><?php echo $user["education"]; ?></h4>
            <p><?php echo $user["bio"]; ?></p>
            <div>
                <a href="<?php echo $user["website"]; ?>"><?php echo $user["website"]; ?></a>
                <a href="<?php echo $user["instagram"]; ?>"><?php echo $user["instagram"]; ?></a>
                <a href="<?php echo $user["github"]; ?>"><?php echo $user["github"]; ?></a>
                <a href="<?php echo $user["linkedin"]; ?>"><?php echo $user["linkedin"]; ?></a>
            </div>
            <?php if (intval(User::checkban($_SESSION["id"])) === 0):  ?>
                <?php if (!empty($_GET["id"]) && $_GET["id"] !== $_SESSION["id"]): ?>
                    <?php if (Follow::isFollowing(Cleaner::cleanInput($_GET["id"]), Cleaner::cleanInput($_SESSION["id"]))): ?>
                    <div class="follow" data-profile_id="<?php echo Cleaner::cleanInput($_GET["id"]) ?>" data-user_id="<?php echo Cleaner::cleanInput($_SESSION["id"]); ?>" style="display: none;">Follow</div>
                    <div class="unfollow" data-profile_id="<?php echo Cleaner::cleanInput($_GET["id"]) ?>" data-user_id="<?php echo Cleaner::cleanInput($_SESSION["id"]); ?>">Unfollow</div>
                    <?php else: ?>
                    <div class="follow" data-profile_id="<?php echo Cleaner::cleanInput($_GET["id"]) ?>" data-user_id="<?php echo Cleaner::cleanInput($_SESSION["id"]); ?>">Follow</div>
                    <div class="unfollow" data-profile_id="<?php echo Cleaner::cleanInput($_GET["id"]) ?>" data-user_id="<?php echo Cleaner::cleanInput($_SESSION["id"]); ?>" style="display: none;">Unfollow</div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($_GET["id"] != $_SESSION["id"]): ?>
              
            <div class="profile__info__report">

            <a href="new_report.php?userid=<?php echo $user['id'] ; ?>">
            <h3>Report user</h3>
            </a>
            </div>  
            <?php endif; ?> 
      
            <?php if (User::checkModerator($uid)): ?>
                <div>
                    <a href="moderator_overview.php?id=<?php echo Cleaner::cleanInput($_GET["id"]) ?>">
                        <?php if (User::checkBan(Cleaner::cleanInput($_GET["id"]))): ?>
                            Retract ban user : <?php echo $user["username"]; ?>
                        <?php else: ?>
                            Ban user: <?php echo $user["username"]; ?>
                        <?php endif; ?>    
                    </a>
                </div>
            <?php endif; ?>
        </div>    
        </div>
        <?php if (User::checkUserRole($uid) !== "user"): ?>
        <div class="getRegisterLink">
            <input type="text" class="specialRegisterLink">
            <button class="getRegisterLinkBtn">Get Alumni Link</button>
            <script src="./javascript/getLink.js"></script>
        </div>
        <?php endif; ?>
    </section>
    
    <section class="posts">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post__img">
                <?php if (Showcase::checkShowcase($post["id"], $uid)): ?>
                    <?php if ($uid === $post["user_id"]): ?>
                        <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts hidden" data-id="<?php echo $post["id"]; ?>">
                        <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase" class="heartsfull" data-id="<?php echo $post["id"]; ?>">
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($uid === $post["user_id"]): ?>
                        <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts" data-id="<?php echo $post["id"]; ?>">
                        <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase" class="heartsfull hidden" data-id="<?php echo $post["id"]; ?>">
                    <?php endif; ?>
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
                <?php endif; ?> 
                <?php $pid = $post["id"]; ?>
                <div class="post__actions">
                    <?php if (intval(User::checkban($_SESSION["id"])) === 0): ?>
                        <?php if (Like::getLikesbyPostandUser($pid, $uid)): ?>
                            <div class="like hidden" data-id="<?php echo $pid; ?>">
                                <p class="like__text">❤ Like</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                            <div class="liked" data-id="<?php echo $pid; ?>">
                                <p class="liked__text">❤ Liked</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="like" data-id="<?php echo $pid; ?>">
                                <p class="like__text">❤ Like</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                            <div class="liked hidden" data-id="<?php echo $pid; ?>">
                                <p class="liked__text">❤ Liked</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>                    
                        <?php if ($uid == $_GET["id"]): ?>
                            <a href="edit_post.php?pid=<?php echo($post['id']); ?>&uid=<?php echo($_SESSION["id"]); ?>">
                                <img class="edit_icon" src="./assets/icon_edit.svg" alt="edit pencil :sparkle:">
                            </a>  
                            <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                            </a>
                        <?php elseif($uid === $_GET["id"] || User::checkban($_SESSION["id"]) === 0):?>                                    
                            <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                            </a>      
                        <?php endif; ?> 
                    <?php endif; ?>
                </div>
                <?php if ($uid !== $_GET["id"]): ?>
                    <div class="profile__info__report">
                        <a href="new_report.php?postid=<?php echo $post['id']; ?>">
                            <h3>Report post</h3>
                        </a>
                    </div>
                <?php endif; ?> 
            </div>
        </div>              
    <?php endforeach; ?>
    </section>

    <?php if ($postCount > $postsPerPage): ?>
        <?php if ($pageNum > 1): ?>
            <a href="home.php?page=<?php echo $pageNum-1 ?>" class="next_page">Previous page</a>
        <?php endif; ?>
        <a href="home.php?page=<?php echo $pageNum+1 ?>" class="next_page">Next page</a>
    <?php endif; ?>

    <script src="./javascript/like.js"></script>
    
  <script src="./javascript/showcase.js"></script>
</body>
<?php if (!empty($_GET["id"]) && $_GET["id"] !== $_SESSION["id"]): ?>
<script src="./javascript/follow_unfollow.js"></script>
<?php endif; ?>
</html>