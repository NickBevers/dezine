<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");
    if(!Security::isLoggedIn()) { header('Location: login.php');}

    $uid = Cleaner::cleanInput($_SESSION["id"]);
    if(!User::checkModerator($uid)){
        header('Location: home.php');
    }
    $reports = Report::getReports();
    $posts = Post::getAllPosts();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <title>Moderator Overviewpage</title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <main>
        <h1>Moderator Overviewpage</h1>
        <div class="reports form form--profile"> 
            <!-- styling nog doen -->
            <?php foreach($reports as $report): ?>
                <?php if($report["post_id"] !== 0): ?>
                <div class="report postreport">
                    <?php $post = Post::getPostbyPostId($report["post_id"]); ?>
                    <a href="detailsPost.php?pid=<?php echo $post["id"];?>">
                        <img src="<?php echo $post["image"]; ?>" class="reports__post__img" alt="reported post">
                    </a>
                    <div>
                        <p> Reason for report: <?php echo $report["reason"]; ?></p>
                        <p> Date and time of report: <?php echo $report["timestamp"]; ?></p>
                    </div>
                </div>
                <?php endif; ?> 
                <?php if($report["post_id"] == 0): ?>
                <div class="report userreport">
                    <?php $post = Post::getPostbyPostId($report["post_id"]); ?>
                    <img src="<?php echo(User::getProfileImagebyId($report["user_id"])["profile_image"]); ?>" class="reports__user__img" alt="profile picture <?php echo($report["user_id"]); ?>">
                    <p class="reports__user__username"> Username: 
                        <a href="profile.php?pid=<?php echo $report["user_id"];?>">
                            <?php echo(User::getUserNamebyId($report["user_id"])['username']); ?>
                        </a> 
                    </p>
                    <div>
                        <p> Reason for report: <?php echo $report["reason"]; ?></p>
                        <p> Date and time of report: <?php echo $report["timestamp"]; ?></p>
                    </div>
                </div>
                <?php endif; ?> 
            <?php endforeach; ?>     
            
        </div>        
    </main>
</body>
</html>