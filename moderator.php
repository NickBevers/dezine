<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");
    if(!Security::isLoggedIn()) { header('Location: login.php');}

    $uid = Cleaner::cleanInput($_SESSION["id"]);
    if(!User::checkModerator($uid)){
        header('Location: home.php');
    }

    if(isset($_GET["id"])){
        $banId = Cleaner::cleanInput($_GET["id"]);
        $user = User::getUserbyId($banId);
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
            <!-- styling nog doen -->
            <?php if(isset($_GET["id"])): ?>
                <div class="alert alert-success hidden"></div>

                <?php if(User::checkBan($banId)): ?>
                    <div class="banning hidden">
                        <h2>Would you like to ban user <?php echo $user["username"]; ?>?</h2>
                        <button href="#" class="btn secondary__btn secondary__btn-signup ban" data-id="<?php echo $banId; ?>">Ban user</button>
                    </div>
                    <div class="banned">
                        <h2>Would you like to retract the ban against user <?php echo $user["username"]; ?>?</h2>
                        <button href="#"class="btn secondary__btn secondary__btn-signup unban" data-id="<?php echo $banId; ?>">Retract Ban</button>
                    </div>        
                <?php else: ?>    
                    <div class="banning">
                        <h2>Would you like to ban user <?php echo $user["username"]; ?>?</h2>
                        <button href="#" class="btn secondary__btn secondary__btn-signup ban" data-id="<?php echo $banId; ?>">Ban user</button>
                    </div>
                    <div class="banned hidden">
                        <h2>Would you like to retract the ban against user <?php echo $user["username"]; ?>?</h2>
                        <button href="#"class="btn secondary__btn secondary__btn-signup unban" data-id="<?php echo $banId; ?>">Retract Ban</button>
                    </div>  
                <?php endif; ?>
        <?php endif; ?>
        <?php if(!isset($_GET["id"])): ?>
        <div class="reports form form--profile"> 
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
        <?php endif; ?>      
    </main>
    <script src="./javascript/add_remove_ban.js"></script>
</body>
</html>