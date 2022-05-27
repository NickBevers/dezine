<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Security;
    use Dezine\Helpers\Cleaner;
    use Dezine\Auth\User;
    use Dezine\Actions\Report;
    use Dezine\Content\Post;
    use Dezine\Actions\Warning;

    Validate::start();
    
    if(!Security::isLoggedIn()) { header('Location: login.php');}

    $uid = Cleaner::xss(Cleaner::cleanInput($_SESSION["id"]));
    if (!User::checkModerator($uid)) {
        header('Location: home.php');
    }

    if (isset($_GET["id"])) {
        $banId = Cleaner::cleanInput($_GET["id"]);
        $user = User::getUserbyId($banId);
    }
     
    if (!empty($_GET["warn_uid"]) && !empty($_POST)) {
        $user_id = Cleaner::cleanInput($_GET["warn_uid"]);
        $reason = $_POST["warning_reason"];
        Warning::sendWarning($uid, $user_id, $reason);
    }

    $reports = Cleaner::xss(Report::getReports());
    $posts = Cleaner::xss(Post::getAllPosts());

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
        <h1 class="mod__title">Moderator Overviewpage</h1>
        <?php if(isset($_GET["id"])): ?>
            <div class="alert alert-success hidden"></div>
            <div class="banning <?php if (User::checkBan($banId)) { echo "hidden"; } ?>">
                <div class="mod__ban">
                    <h2>Would you like to ban user <?php echo $user["username"]; ?>?</h2>
                    <button href="#" class="btn secondary__btn secondary__btn-signup ban" data-id="<?php echo $banId; ?>">Ban user</button>
                </div>
            </div>
            <div class="banned <?php if (!User::checkBan($banId)) { echo "hidden"; } ?>">
                <div class="mod__ban">
                    <h2>Would you like to retract the ban against user <?php echo $user["username"]; ?>?</h2>
                    <button href="#"class="btn secondary__btn secondary__btn-signup unban" data-id="<?php echo $banId; ?>">Retract Ban</button>
        </div>
            </div>
        <?php elseif(isset($_GET["warn_uid"])): ?>
        <div class="warnings">
            <form action="" method="post" class="form form--profile">
                <h2>Would you like to warn a user?</h2>
                <div class="form__field" id="form__report__reason">
                    <input type="hidden" name="uid" value="<?php echo $_GET["warn_uid"] ?>">
                    <label for="warning_reason" class="form__label">Reason</label>
                    <input type="warning_reason" name="warning_reason" class="form-control" id="warning_reason" required
                        placeholder="the reason for your report">
                </div>
                <button type="submit" class="btn secondary__btn secondary__btn-signup">Send</button>
            </form>
        </div>
        <?php else: ?>
        <div class="reports form form--profile"> 
            <?php foreach ($reports as $report): ?>
                <?php if (intval($report["archived"]) == 0): ?>
                    <div class="report">
                    <?php $post = Post::getPostbyPostId($report["post_id"]); ?>
                        <?php if (intval($report["post_id"]) !== 0): ?>
                            <a href="detailsPost.php?pid=<?php echo $post["id"];?>">
                                <img src="<?php echo $post["image"]; ?>" class="reports__post__img" alt="reported post">
                            </a>
                        <?php elseif (intval($report["post_id"]) == 0): ?>
                            <img src="<?php echo(User::getProfileImagebyId($report["user_id"])["profile_image"]); ?>" class="reports__user__img" alt="profile picture <?php echo($report["user_id"]); ?>">
                            <p class="reports__user__username"> Username: 
                                <a href="profile.php?pid=<?php echo $report["user_id"];?>">
                                    <?php echo(User::getUserNamebyId($report["user_id"])['username']); ?>
                                </a> 
                            </p>
                        <?php endif; ?> 
                        <div class="report__details">
                            <p><strong>Reason for report: </strong><?php echo $report["reason"]; ?></p>
                            <p><strong>Date and time of report: </strong><?php echo $report["timestamp"]; ?></p>
                            <button href="#" class="archive primary__btn" data-report_id="<?php echo($report["id"]); ?>" >Archive report</button>
                        </div>
                    </div>
                <?php endif; ?> 
            <?php endforeach; ?>  
        </div>        
        <?php endif; ?>      
        <script src="./javascript/add_remove_ban.js"></script>
        <script src="./javascript/archive_report.js"></script>
    </main>
</body>
</html>