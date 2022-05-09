<?php

    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");
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
    var_dump($post_id);
   
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
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

        <h4>Send Report</h4>

        <div>
            <div class="mb-3" id="reason--div">
                <label for="reason" class="form-label">Reason</label>
                <input type="reason" name="reason" class="form-control" id="reason" required
                    placeholder="the reason for your report">

            </div>
            <div id="lol" style='display:none;'>
                <p>please give a reason for the report</p>

            </div>
            <div id="loll" style='display:none;'>
                <p>Thank you for helping keep the Dezine community safe and fun for eyeryone. Remember, we don't reveal
                    who submitted reports to the person.</p>
                <p>below you see the report you send</p>
                <div id="report--reason"></div>
                <div>
                    <p>Our team will review the post and if it violates our <a href="community_guidelines.php">Community Guidelines</a> or <a href="terms_of_use.php">Terms of Use</a>, we will remove it, the Dezine team</p>
                </div>
            </div>

            
            <div id="report--button"></div>
            <a href="#" class="btn" id="btnAddReport" data-reported_user_id="<?php echo $reported_user_id; ?>"
                data-post_id="<?php echo $post_id; ?>">Submit Report</a>
        </div>


        <br>
        <br>


    </main>
    <script src="./javascript/report.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>



</body>

</html>