<?php

    include_once(__DIR__ . "/autoloader.php");
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");
	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    $post_id = $_GET['postid'];
    $reported_user_id = $_GET['userid'];
    $user_id = $_SESSION['id'];
    $report = new Report();
    
   
    $reports_post = $report->getReportedPostbyId($post_id);
    $reports_user = $report->getReportedUserbyId($reported_user_id);

  
    if (!empty($_POST)) {
    
        $reason = $_POST['reason'];
        $report->setPostid($post_id);
        $report->setReportedUserId($reported_user_id);
        $report->setReason($reason);

        //$report->getPostid($postid);
       

        //$report->getUsername($username);
       
       

       
        try{




            if($report->sendReport($user_id)){
                //header("Location: home.php");
              } else{
                $error = "Something has gone wrong, please try again.";
              }
           

        } catch (Throwable $error) {
                $error = $error->getMessage();
        }
    }
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

    <?php if($_GET['postid'] != 0 ): ?>
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
            <img src="<?php echo $reports_user["profile_image"]; ?>" alt="profile image <?php echo $reports_user["username"]; ?>">
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
        <div class="mb-3">
                <label for="reason" class="form-label">Reason</label>
                <input type="reason" name="reason" class="form-control" id="reason" 
                    placeholder="the reason for your report">
            </div>

            <ul class="lol">
            <li></li>
           </ul>

            

            <a href="#" class="btn" id="btnAddReport" data-user_id="<?php echo Cleaner::cleanInput($_SESSION["id"]); ?>" data-post_id="<?php echo Cleaner::cleanInput($post_id); ?>">Submit Report</a>
        </div>
           
     
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>

<script src="./javascript/report.js"></script>

</body>

</html>