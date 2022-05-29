<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Auth\User;
    use Dezine\Content\Post;
    use Dezine\Actions\Like;
    use Dezine\Content\Showcase;
    use Dezine\Actions\Follow;
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Security;
    use Dezine\Helpers\Cleaner;

    Validate::start();
    if (!Security::isLoggedIn()) {header('Location: login.php');}

    if (empty($_GET["id"])) {
        if (empty($_SESSION["id"])) {
            header('Location: login.php');
        } else {
            $id = $_SESSION["id"];
            $profileUser = intval($_SESSION["id"]);
            header("Location: profile.php?id=$id");
        }
    } else {
        $profileUser = intval($_GET["id"]);
    }

    $user = Cleaner::xss(User::getUserbyId($profileUser));
    if (empty($user)) {header('Location: home.php');}

    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    
    if (isset($_GET["page"]) && $_GET["page"] > 1) {
        $pageNum  = $_GET["page"];
        $posts = Post::getPostbyId($profileUser, $pageNum*$postsPerPage, $postsPerPage);
    } else {
        $pageNum  = 1;
        $posts = Post::getPostbyId($profileUser, 0, $postsPerPage);
    }

    $posts = Cleaner::xss($posts);
    $uid = Cleaner::cleanInput($_SESSION["id"]);
    $gid = Cleaner::cleanInput($_GET["id"]);
    $role = $user["user_role"];

    $followCount = Follow::getFollowCount($uid);

    if (isset($_POST["moderator"])) {
        if ($_POST["moderator"] === "assign") {
            $role = "moderator";
            User::UpdateUserRole($role, $user["id"]);
        } else {
            $role = "user";
            User::UpdateUserRole($role, $user["id"]);
        }
        header("Refresh:0");
    }
?><!DOCTYPE html>
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
    <?php unset($_SESSION['flash_error']); endif; ?>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <section class="profile__info">
        <div class="profile__info__img">
            <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
        </div>
        <div class="profile__info__block">
            <div class="profile__info__details">
                <div class="profile__info__details__username">
                    <h1><?php echo $user["username"]; ?></h1>
                    <?php if($user["user_role"] !== "user" && User::checkUserRole($uid) !== "user"): ?>
                    <img src="assets\icon_check.svg" id="profile__verified" alt="verified icon">
                    <?php endif; ?>
                    <?php if(intval($user["id"]) !== intval($uid) && User::checkUserRole($uid) === "admin"): ?>
                    <form action="#" method="post">
                        <?php if($user["user_role"] === "user"): ?>
                        <button name="moderator" value="assign" type="submit" class="btn moderator__btn">Make
                            moderator</button>
                        <?php endif; ?>
                        <?php if($user["user_role"] !== "user"): ?>
                        <button name="moderator" type="delete" class="btn moderator__btn">Delete moderator role</button>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>
                </div>
                <h4><?php echo $user["education"]; ?></h4>
                <p><?php echo $user["bio"]; ?></p>
                <?php if (intval(User::checkban($uid)) === 0):  ?>
                    <?php if (!empty($gid) && $gid !== $uid): ?>
                        <div class="profile__info-follow">
                            <?php if(Follow::isFollowing($gid, $uid)): ?>
                                <div class="follow" data-profile_id="<?php echo $gid; ?>" data-user_id="<?php echo $uid; ?>" style="display: none;">
                                    <p>Follow</p>
                                </div>
                                <div class="unfollow" data-profile_id="<?php echo $gid ?>" data-user_id="<?php echo $uid; ?>">
                                    <p>Unfollow</p>
                                </div>
                            <?php else: ?>
                                <div class="follow" data-profile_id="<?php echo $gid ?>" data-user_id="<?php echo $uid; ?>">
                                    <p>Follow</p>
                                </div>
                                <div class="unfollow" data-profile_id="<?php echo $gid ?>" data-user_id="<?php echo $uid; ?>" style="display: none;">
                                    <p>Unfollow</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="profile__info__followers">
                    <?php if($_GET["id"] == $_SESSION["id"]): ?>   
                        <p>Followers : <?php echo $followCount ?></p>                    
                    <?php endif; ?> 
                </div>
                <div class="profile__info__btn">
                    <a href="showcase.php?id=<?php echo $gid; ?>" class="btn primary__btn">
                        Showcase user
                    </a>
                    <?php if($gid != $uid): ?>   
                        <a href="new_report.php?userid=<?php echo $user['id'] ; ?>" class="btn primary__btn">
                            Report user
                        </a>
                    <?php endif; ?> 
                </div>
                <div class="profile__info__moderator">
                    <?php if (User::checkUserRole($uid) !== "user"): ?>
                        <div class="getRegisterLink">
                            <button class="getRegisterLinkBtn btn moderator__btn">Get Alumni Link</button>
                            <script src="./javascript/getLink.js"></script>
                        </div>
                        <?php if(intval($uid) !== intval(Cleaner::xss($gid))): ?>
                        <a href="moderator.php?warn_uid=<?php echo $gid; ?>" class="btn moderator__btn">Warn user</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (User::checkModerator($uid)): ?>
                        <a href="moderator.php?id=<?php echo $gid; ?>" class="btn moderator__btn">
                            <?php if (User::checkBan($gid)): ?>
                                Retract ban
                            <?php else: ?>
                            Ban user
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile__info-socials">
                <?php if(!empty($user["linkedin"])): ?>
                <a href="<?php echo $user["linkedin"]; ?>" target="_blank"><img src="./assets/linkedin_icon.svg"
                        alt="linkedin icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["website"])): ?>
                <a href="<?php echo $user["website"]; ?>" target="_blank"><img src="./assets/web_small_icon.svg"
                        alt="website icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["instagram"])): ?>
                <a href="<?php echo $user["instagram"]; ?>" target="_blank"><img src="./assets/insta_small_icon.svg"
                        alt="instagram icon"></a>
                <?php endif; ?>
                <?php if(!empty($user["github"])): ?>
                <a href="<?php echo $user["github"]; ?>" target="_blank"><img src="./assets/github_icon.svg"
                        alt="github icon"></a>
                <?php endif; ?>
            </div>
        </div>
    </section>    
    <section class="warning_messages">    
        <?php $warnings = User::checkWarning($uid); if($warnings && $uid === Cleaner::xss($gid)): ?>
            <div class="warning_user">
                <?php foreach ($warnings as $warning):  ?>
                    <div class="warning__message">
                        <h3>You have received a warning!</h3>
                        <p>Our content monitors have determined that your behavior at Dzine has been in violation of our 
                            <a href="terms_of_use.php">Terms of Use</a>.
                        </p>
                        <p>Moderator Note: <?php echo $warning["warning"] ; ?></p>
                        <p>Please abide by the 
                            <a href="community_guidelines.php">Community Guidlines</a> 
                            so that Dzine can be a fun place for everyone!
                        </p>
                        <p>Close this message by agreeing to our 
                            <a href="terms_of_use.php">Terms of Use</a> 
                        </p>
                        <div class="btn primary__btn agreement_button" data-warning_id="<?php echo Cleaner::cleanInput($warning["id"]); ?>">click for agreement
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
    <?php if (intval(User::checkban($uid)) === 1 && $uid === Cleaner::xss($gid)): ?>
    <section class="ban__message">
        <h3>You have been banned!</h3>
        <p>Your behavior on the platform has not been within community guidelines. As a result your interactions have been limited on the platform.</p>
        <p>If you want to get your ban revoked, please make an appointment with the platform moderators to discuss your case.</p>
        <a href="mailto:dezine@thomasmore.be" class="btn primary__btn">Make appointment</a>
    </section>
    <?php endif; ?>
    <?php if(empty($posts) && $uid === Cleaner::xss($gid)): ?>
        <div class="showcase__empty">
            <h2 class="showcase__title-h2">You haven't added any posts!</h2>
            <div class="showcase__empty-message">
                <a class="btn primary__btn" href="new_post.php">Add posts to your profile</a>  
            </div>
        </div>
    <?php elseif(empty($posts)): ?>
        <div class="showcase__empty">
            <h2 class="showcase__title-h2"><?php echo Cleaner::xss($user["username"]); ?> hasn't yet added any posts!</h2>
        </div>
    </div>
    <?php else: ?>
    <section class="posts">
        <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post__img">
                <?php if (Showcase::checkShowcase($post["id"], $uid)): ?>
                <?php if ($uid === $post["user_id"]): ?>
                <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts hidden"
                    data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase" class="heartsfull"
                    data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                <?php endif; ?>
                <?php else: ?>
                <?php if ($uid === $post["user_id"]): ?>
                <img src="./assets/hearts_icon.svg" alt="showcase icon" id="post__img-showcase" class="hearts"
                    data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                <img src="./assets/hearts_full_icon.svg" alt="showcase icon" id="post__img-showcase"
                    class="heartsfull hidden" data-id="<?php echo $post["id"]; ?>" data-uid="<?php echo $uid; ?>">
                <?php endif; ?>
                <?php endif; ?>
                <a class="post__link" href="detailsPost.php?pid=<?php echo $post["id"];?>">
                    <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
                </a>
            </div>
            <div class="post__info">
                <a class="post__link" href="detailsPost.php?pid=<?php echo $post["id"];?>">
                    <h3 class="post__title"><?php echo $post["title"] ?></h3>
                </a>
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
                    <?php if (intval(User::checkban($uid)) === 0): ?>
                        <?php if (Like::getLikesbyPostandUser($pid, $uid)): ?>
                            <div class="like hidden" data-id="<?php echo $pid; ?>">
                                <p class="like__text"><img src="./assets/like_empty_icon.svg" alt="Like heart"> Like</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                            <div class="liked" data-id="<?php echo $pid; ?>">
                                <p class="liked__text"><img src="./assets/like_full_icon.svg" alt="Like heart"> Liked</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="like" data-id="<?php echo $pid; ?>">
                                <p class="like__text"><img src="./assets/like_empty_icon.svg" alt="Like heart"> Like</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                            <div class="liked hidden" data-id="<?php echo $pid; ?>">
                                <p class="liked__text"><img src="./assets/like_full_icon.svg" alt="Like heart"> Liked</p>
                                <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>  
                        <div class="post__actions-edit">
                            <?php if ($uid == $gid): ?>
                                <a href="edit_post.php?pid=<?php echo($post['id']); ?>&uid=<?php echo($uid); ?>">
                                    <img class="edit_icon" src="./assets/icon_edit.svg" alt="edit pencil :sparkle:">
                                </a>  
                                <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                                </a>
                            <?php elseif (User::checkModerator($uid)):?>                                    
                                <a href="delete_post.php?pid=<?php echo($post['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <img class="trash_icon" src="./assets/icon_trash.svg" alt="trash can">
                                </a>      
                            <?php endif; ?>
                            <?php if ($uid !== $gid): ?>
                                <div class="post__info__report">
                                    <a href="new_report.php?postid=<?php echo $post['id']; ?>">
                                        <img src="./assets/report_icon.svg" alt="report user icon" class="report_icon">
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>          
                    <?php endif; ?>
                </div>                
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
    <?php if ($postCount > $postsPerPage): ?>
        <?php if ($pageNum > 1): ?>
            <a href="home.php?page=<?php echo $pageNum-1 ?>" class="next_page">Previous page</a>
        <?php endif; ?>
        <a href="home.php?page=<?php echo $pageNum+1 ?>" class="next_page">Next page</a>
    <?php endif; ?>
    <script src="./javascript/like.js"></script>
    <script src="./javascript/showcase.js"></script>
    <script src="./javascript/remove_warning.js"></script> 
    <script src="./javascript/flag.js"></script>
</body>
<?php if (!empty($gid) && $gid !== $uid): ?>
    <script src="./javascript/follow_unfollow.js"></script>
<?php endif; ?>
</html>