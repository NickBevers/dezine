<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Dezine\Helpers\Validate;
    use \Dezine\Helpers\Security;
    use \Dezine\Helpers\Cleaner;
    use \Dezine\Actions\Report;

    Validate::start();

    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    if (isset($_GET['postid'])) {
        $post_id = Cleaner::xss($_GET['postid']);
    } else {
        $post_id = "";
    }

    if (isset($_GET['userid'])) {
        $reported_user_id = Cleaner::xss($_GET['userid']);
    } else {
        $reported_user_id = "";
    }

    $user_id = $_SESSION['id'];
    
    $report = new Report();
    $reports_post = Cleaner::xss($report->getReportedPostbyId($post_id));
    $reports_user = Cleaner::xss($report->getReportedUserbyId($reported_user_id));
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dezine</title>
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
</head>
<body class="container">
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <main>        
        <div id="form__report__error" class="alert alert-danger">
            <p>please give a reason for the report</p>
        </div>
        <div id="form__report__message" class="alert alert-success">
            <p>Thank you for helping keep the Dezine community safe and fun for eyeryone. Remember, we don't reveal
                who submitted reports to the person. Below you can see the report you send</p>
            <div id="form__report__reason__body"></div>
            <div>
                <p>Our team will review the post and if it violates our <a href="community_guidelines.php">Community
                        Guidelines</a> or <a href="terms_of_use.php">Terms of Use</a>, we will remove it, the D-zine
                    team</p>
            </div>
        </div>
        <div class="form form--profile form--report">
            <h2 class="form--report_h2">Send Report</h2>
            <div class="form__field" id="form__report__reason">
                <label for="reason" class="form__label">Reason</label>
                <input type="reason" name="reason" class="form-control" id="reason" required placeholder="the reason for your report">
            </div>            
            <div id="report__button"></div>
            <a href="#" class="btn secondary__btn secondary__btn-signup" id="btn__add__Report" data-reported_user_id="<?php echo $reported_user_id; ?>" data-post_id="<?php echo $post_id; ?>" data-post_user_id="<?php echo $user_id; ?>">Submit Report</a>
        </div>
        <?php if ($post_id !== "" && $post_id !== null): ?>
        <div class="post--single">
            <div class="post post--single__content">
                <img src=<?php echo $reports_post["image"] ?> alt=<?php echo $reports_post["title"] ?>>
                <div class="post__info">
                    <h3 class="post__title"><?php echo $reports_post["title"] ?></h3>
                    <?php if (isset($_SESSION["id"])): ?>
                    <p><?php echo $reports_post["description"] ?></p>
                    <?php $tags = json_decode($reports_post["tags"]); ?>
                    <div class="post__info__tags">
                        <?php foreach ($tags as $t): ?>
                            <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>            
        </div>
        <?php else: ?>
        <section class="profile__info">
            <div class="profile__info__img">
                <img src="<?php echo $reports_user["profile_image"]; ?>"
                    alt="profile image <?php echo $reports_user["username"]; ?>">
            </div>
            <div class="profile__info__details">
                <h1><?php echo $reports_user["username"]; ?></h1>
                <h4><?php echo $reports_user["education"]; ?></h4>
                <p><?php echo $reports_user["bio"]; ?></p>
            </div>
        </section>
        <?php endif; ?>        
    </main>
    <script src="./javascript/report.js"></script>
</body>
</html>