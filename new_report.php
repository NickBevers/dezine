<?php
    include_once("bootstrap.php");
    use \Helpers\Validate;
    use \Helpers\Security;
    use \Helpers\Cleaner;
    use Classes\Actions\Report;
    Validate::start();

	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    if(isset($_GET['postid']))
    {
        $post_id = Cleaner::cleanInput($_GET['postid']);
    }
    else{
    $post_id = "";
    }

    if(isset($_GET['userid']))
    {
        $reported_user_id = Cleaner::cleanInput($_GET['userid']);
    }
    else{
    $reported_user_id = "";
    }

    $user_id = Cleaner::cleanInput($_SESSION['id']);
    $report = new Report();
    
    $reports_post = $report->getReportedPostbyId($post_id);
    $reports_user = $report->getReportedUserbyId($reported_user_id);

?>
<!DOCTYPE html>
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
        <?php if($post_id !== "" && $post_id !== NULL ): ?>
        <div class="post">
            <img src=<?php echo $reports_post["image"] ?> alt=<?php echo $reports_post["title"] ?>>
            <div class="post__info">
                <h3><?php echo $reports_post["title"] ?></h3>
                <?php if(isset($_SESSION["id"])): ?>
                <p><?php echo $reports_post["description"] ?></p>

                <?php endif; ?>
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


        <div class="form form--profile">
            <h2>Send Report</h2>
            <div class="form__field" id="form__report__reason">
                <label for="reason" class="form__label">Reason</label>
                <input type="reason" name="reason" class="form-control" id="reason" required placeholder="the reason for your report">
            </div>
            <div id="form__report__error">
                <p>please give a reason for the report</p>
            </div>
            <div id="form__report__message">
                <p>Thank you for helping keep the Dezine community safe and fun for eyeryone. Remember, we don't reveal
                    who submitted reports to the person.</p>
                <p>below you see the report you send</p>
                <div id="form__report__reason__body"></div>
                <div>
                    <p>Our team will review the post and if it violates our <a href="community_guidelines.php">Community
                            Guidelines</a> or <a href="terms_of_use.php">Terms of Use</a>, we will remove it, the D-zine
                        team</p>
                </div>
            </div>

            <div id="report__button"></div>
            <a href="#" class="btn secondary__btn secondary__btn-signup" id="btn__add__Report" data-reported_user_id="<?php echo $reported_user_id; ?>" data-post_id="<?php echo $post_id; ?>">Submit Report</a>
        </div>
        <?php include_once("./includes/footer.inc.php"); ?>
    </main>
    <script src="./javascript/report.js"></script>
</body>
</html>