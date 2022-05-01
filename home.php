<?php 
    include_once(__DIR__ . "/autoloader.php");
    
    include_once("./helpers/Security.help.php");
	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }
    
    $postsPerPage = 18;
    $postCount = Post::getPostsCount();
    $uid = $_SESSION["id"];

    if(isset($_GET['sort'])){
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
    } else{
        $sorting = '';
    }
    if($sorting === ''){$posts = Post::getSomePosts("desc", 0, $postsPerPage);}
    else if(!empty($_GET["search"]) && $sorting !== "follow"){
        $search_term = Cleaner::cleanInput($_GET["search"]);
        if (isset($_GET["page"]) && $_GET["page"] > 1) { 
            $pageNum  = $_GET["page"];
            $posts = Post::getSearchPosts($search_term, $sorting, $pageNum*$postsPerPage, $postsPerPage);

        } else {
            $pageNum  = 1;
            $posts = Post::getSearchPosts($search_term, $sorting, 0, $postsPerPage);           
            // weet niet of dit de juiste manier is voor melding waneer er geen posts verzonden zijn
        };
    } else if(!empty($_GET["search"]) && $sorting === "follow"){
        if (isset($_GET["page"]) && $_GET["page"] > 1) { 
            $pageNum  = $_GET["page"];
            $posts = Post::getFollowedSearchPosts($uid, $search_term, $pageNum*$postsPerPage, $postsPerPage);

        } else {
            $pageNum  = 1;
            $posts = Post::getFollowedSearchPosts($uid, $search_term, 0, $postsPerPage);           
        };
    } else {
        if (isset($_GET["page"]) && $_GET["page"] > 1 && $sorting !== "follow"){ 
            $pageNum  = $_GET["page"];
            $posts = Post::getSomePosts($sorting, $pageNum*$postsPerPage, $postsPerPage);
    
        } else if (isset($_GET["page"]) && $_GET["page"] > 1 && $sorting === "follow"){ 
            $pageNum  = $_GET["page"];
            $posts = Post::getFollowedPosts($uid, $sorting, $pageNum*$postsPerPage, $postsPerPage);
    
        } else{
            $pageNum  = 1;
            if($sorting !== "follow"){
                $posts = Post::getSomePosts($sorting, 0, $postsPerPage);
            } else{
                $posts = Post::getFollowedPosts($uid, 0, $postsPerPage);
            }
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

    <select name="sort" id="feedSort" class="feedSort" onchange="sort(this.value)">
        <option value="date_desc"  <?php if(isset($_GET["sort"]) && $_GET['sort'] === 'date_desc'|| !isset($_GET["sort"])):?>selected="selected"<?php endif;?>>Date (newest first)</option>
        <option value="date_asc" <?php if(isset($_GET["sort"]) && $_GET['sort'] === 'date_asc'):?>selected="selected"<?php endif;?>>Date (oldest first)</option>
        <option value="following" <?php if(isset($_GET["sort"]) && $_GET['sort'] === 'following'):?>selected="selected"<?php endif;?>>following</option>
    </select>

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
        <div class="posts__bkg">                
            <div class="posts__user">
                <?php $user = User::getUserbyId($post["user_id"]); ?>
                <img src="<?php echo $user["profile_image"]; ?>" alt="profile image <?php echo $user["username"]; ?>">
                <a href="profile.php?id=<?php echo $post["user_id"]; ?>" class="posts__user__name">
                    <h3><?php echo $user["username"] ?></h3>         
                </a>
            </div>               
            <div class="post">
                <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
                <div class="post__colors">
                    <?php foreach(json_decode($post["colors"]) as $color): ?>
                        <div class="post__color" style="background-color: <?php echo $color ?>; width: 20px; height: 20px;"></div>
                    <?php endforeach; ?>
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
                    <?php endif; ?>  
                    <?php $pid = $post["id"]; ?>
                    <?php if(Like::getLikesbyPostandUser($pid, $uid)): ?>
                    <div class="like hidden" data-id="<?php echo $pid; ?>">
                        <p class="like__text">❤ Like</p>
                        <?php if($uid === $post["user_id"]): ?>
                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                        <?php endif; ?>
                    </div>
                    <div class="liked" data-id="<?php echo $pid; ?>">
                        <p class="liked__text">❤ Liked</p>
                        <?php if($uid === $post["user_id"]): ?>
                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="like" data-id="<?php echo $pid; ?>">
                        <p class="like__text">❤ Like</p>
                        <?php if($uid === $post["user_id"]): ?>
                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                        <?php endif; ?>
                    </div>
                    <div class="liked hidden" data-id="<?php echo $pid; ?>">
                        <p class="liked__text">❤ Liked</p>
                        <?php if($uid === $post["user_id"]): ?>
                        <span class="likes_count"><?php echo Like::getLikes($pid); ?> people like this</span>
                        <?php endif; ?>
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

    <script src="./javascript/like.js"></script>
    <script src="./javascript/feedSort.js"></script>
</body>
</html>