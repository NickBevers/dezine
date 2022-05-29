<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Security;
    use Dezine\Helpers\Cleaner;
    use Dezine\Auth\User;
    use Dezine\Content\Post;
    use Dezine\Actions\Like;

    Validate::start();

    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }
    
    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    $uid = Cleaner::xss($_SESSION["id"]);
    if (empty($_GET["page"])) {
        $pageNum = 0;
    } else {
        $pageNum  = Cleaner::xss($_GET["page"]);
    }

    if (isset($_GET['sort'])) {
        $sorting = Cleaner::cleanInput($_GET['sort']);
        switch ($sorting) {
            case 'date_asc':
                $sorting = "asc";
                break;

            case 'date_desc':
                $sorting = "desc";
                break;

            case 'following':
                $sorting = "follow";
                break;
    
            default:
                $sorting = "desc";
                break;
        }
    } else {
        $sorting = 'desc';
    }
    if (!empty($_GET["search"]) && $sorting !== "follow") {
        $search_term = Cleaner::xss($_GET["search"]);
        if (isset($_GET["page"]) && $_GET["page"] > 1) {
            $pageNum  = Cleaner::xss($_GET["page"]);
            $posts = Post::getSearchPosts($search_term, $sorting, $pageNum*$postsPerPage, $postsPerPage);
        } else {
            $pageNum  = 1;
            $posts = Post::getSearchPosts($search_term, $sorting, 0, $postsPerPage);
            // weet niet of dit de juiste manier is voor melding waneer er geen posts verzonden zijn
        };
    } elseif (!empty($_GET["search"]) && $sorting === "follow") {
        $search_term = Cleaner::xss($_GET["search"]);
        if (isset($_GET["page"]) && $_GET["page"] > 1) {
            $pageNum  = $_GET["page"];
            $posts = Post::getFollowedSearchPosts($uid, $search_term, $pageNum*$postsPerPage, $postsPerPage);
        } else {
            $pageNum  = 1;
            $posts = Post::getFollowedSearchPosts($uid, $search_term, 0, $postsPerPage);
        };
    } else {
        if (isset($_GET["page"]) && $_GET["page"] > 1 && $sorting !== "follow") {
            $pageNum  = $_GET["page"];
            $posts = Post::getSomePosts($sorting, $pageNum*$postsPerPage, $postsPerPage);
        } elseif (isset($_GET["page"]) && $_GET["page"] > 1 && $sorting === "follow") {
            $pageNum  = $_GET["page"];
            $posts = Post::getFollowedPosts($uid, $sorting, $pageNum*$postsPerPage, $postsPerPage);
        } else {
            $pageNum  = 1;
            if ($sorting !== "follow") {
                $posts = Post::getSomePosts($sorting, 0, $postsPerPage);
            } else {
                $posts = Post::getFollowedPosts($uid, 0, $postsPerPage);
            }
        };
    }

    if (isset($_GET["color"])) {
        $getColor = Cleaner::cleanInput($_GET["color"]);
        $posts = Post::getPostsByColor($getColor, 0, $postsPerPage);
    }

    $mostUsedTags = Post::getMostUsedTags();
    $posts = Cleaner::xss($posts);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <title>D-zine</title>
</head>
<body>
<?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <div class="search">
        <div class="welcome-search">
            <?php if (isset($_SESSION['id'])): ?>
                <div>                    
                    <h1> Welcome <?php echo User::getUserNamebyId($_SESSION['id'])["username"]; ?> <img src="assets\eye_icon.svg" alt="eye icon"></h1>
                    <h3>Search in hundreds of projects:</h3>
                </div>
            <?php endif; ?>        
            <section class="search_box">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Search here..." required="required" />
                    <button type="submit" ><img src="assets\icon_search.svg" alt="search"></button>
                </form>

                <select name="sort" id="feedSort" class="feedSort" onchange="sort(this.value)">
                    <option value="date_desc"  <?php if (isset($_GET["sort"]) && $_GET['sort'] === 'date_desc'|| !isset($_GET["sort"])):?>selected="selected"<?php endif;?>>Date (newest first)</option>
                    <option value="date_asc" <?php if (isset($_GET["sort"]) && $_GET['sort'] === 'date_asc'):?>selected="selected"<?php endif;?>>Date (oldest first)</option>
                    <option value="following" <?php if (isset($_GET["sort"]) && $_GET['sort'] === 'following'):?>selected="selected"<?php endif;?>>following</option>
                </select>

                <?php if (!empty($_GET["search"])): ?>
                    <a class="search__cross" href="home.php">X</a>
                <?php endif; ?>
            </section>
        </div>        
        <section class="tags">
            <h3>Most used tags:</h3>
            <ul>
                <?php foreach($mostUsedTags as $key => $tag): ?>
                    <button class="tags__buttons"><a href="home.php?search=<?php echo $key; ?>">#<?php echo $key; ?></a></button>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if (isset($_GET["color"])): ?>
            <a href="home.php">Reset Color filter</a>
        <?php endif; ?>
    </div>
    <section class="posts">
    <?php if (empty($posts)): ?>
        <div class="showcase__empty">
            <h2 class="showcase__title-h2">There are no posts yet!</h2>
            <div class="showcase__empty-message">
                <a class="btn primary__btn" href="new_post.php">Add posts to your profile</a>  
            </div>
        </div>
    <?php endif; ?>
    <?php foreach ($posts as $post): ?>
        <div class="posts__bkg">                
            <div class="posts__user">
                <?php $user = User::getUserbyId($post["user_id"]); ?>
                <img src="<?php echo $user["profile_image"]; ?>" class="posts__user__img"  alt="profile image <?php echo $user["username"]; ?>">
                <a href="profile.php?id=<?php echo $post["user_id"]; ?>" class="posts__user__name">
                    <h3><?php echo $user["username"] ?></h3>
                    <?php if ($user["user_role"] !== "user" && User::checkUserRole($uid) !== "user"): ?>
                        <img src="assets\icon_check.svg" class="posts__user__verified" alt="verified icon">    
                    <?php endif; ?>
                </a>

            </div>               
            <div class="post">
                <a class="post__link" href="detailsPost.php?pid=<?php echo $post["id"];?>">
                    <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?> class="post__img">
                </a>
                <div class="post__colors">
                    <?php $colors = json_decode($post["colors"]); $color_groups = json_decode($post["color_group"]); ?>
                    <?php for ($i = 0; $i < sizeof($colors); $i++): ?>
                        <a href="./home.php?color=<?php echo $color_groups[$i] ?>"><div class="post__color" style="background-color: <?php echo $colors[$i] ?>; width: 20px; height: 20px;"></div></a>
                    <?php endfor; ?>
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
                    <?php if (User::checkban($_SESSION["id"]) === "0"): ?>
                        <div class="post__actions">
                            <div>
                                <?php if (Like::getLikesbyPostandUser($pid, $uid)): ?>
                                <div class="like hidden" data-id="<?php echo $pid; ?>" data-uid="<?php echo $uid; ?>">
                                    <p class="like__text"><img src="./assets/like_empty_icon.svg" alt="Like heart"> Like</p>
                                    <?php if ($uid === $post["user_id"]): ?>
                                    <?php if (Like::getLikes($pid) === 0): ?>
                                        <span class="likes_count">No one likes this yet</span>
                                    <?php else: ?>
                                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="liked" data-id="<?php echo $pid; ?>" data-uid="<?php echo $uid; ?>">
                                    <p class="liked__text"><img src="./assets/like_full_icon.svg" alt="Like heart"> Liked</p>
                                    <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <div class="like" data-id="<?php echo $pid; ?>" data-uid="<?php echo $uid; ?>">
                                    <p class="like__text"><img src="./assets/like_empty_icon.svg" alt="Like heart"> Like</p>
                                    <?php if ($uid === $post["user_id"]): ?>
                                    <?php if (Like::getLikes($pid) === 0): ?>
                                        <span class="likes_count">No one likes this yet</span>
                                    <?php else: ?>
                                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="liked hidden" data-id="<?php echo $pid; ?>" data-uid="<?php echo $uid; ?>">
                                    <p class="liked__text"><img src="./assets/like_full_icon.svg" alt="Like heart"> Liked</p>
                                    <?php if ($uid === $post["user_id"]): ?>
                                    <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>                                          
                            <?php if ($_SESSION["id"] != $post["user_id"]): ?>
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

    <?php if ($postCount > $postsPerPage): ?>
        <?php if ($pageNum > 1): ?>
            <a href="home.php?page=<?php echo $pageNum-1 ?>" class="next_page">Previous page</a>
        <?php endif; ?>
        <a href="home.php?page=<?php echo $pageNum+1 ?>" class="next_page">Next page</a>
    <?php endif; ?>
    </section>
    <script src="./javascript/like.js"></script>
    <script src="./javascript/feedSort.js"></script>
    <script src="./javascript/flag.js"></script>
</body>
</html>