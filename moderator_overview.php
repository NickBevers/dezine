<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");
    if(!Security::isLoggedIn()) { header('Location: login.php');}

    $uid = Cleaner::cleanInput($_SESSION["id"]);
    if(!User::checkModerator($uid)){
        header('Location: home.php');
    }

    $banId = Cleaner::cleanInput($_GET["id"]);
    $user = User::getUserbyId($banId);


    //
    $warning = new Warning();
    $usr= new User();
    $users = $usr->getAllUsers();
     
     

    if (!empty($_POST)) {

   $user_id = $_POST['id'];
    $warning_reason = $_POST['warning_reason'];

    try {
    
    $warning->setReasonWarning($warning_reason);


    $warning->sendWarning($uid, $user_id);
   

     
    } catch (Throwable $error) {
        $error = $error->getMessage();
    }

      
    }



   
   //
    

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
        <div class="form form--profile">

            <div class="alert alert-success hidden"></div>

            <?php if(User::checkBan($banId)): ?>
            <div class="banning hidden">
                <h2>Would you like to ban user <?php echo $user["username"]; ?>?</h2>
                <button href="#" class="btn secondary__btn secondary__btn-signup ban"
                    data-id="<?php echo $banId; ?>">Ban user</button>
            </div>
            <div class="banned">
                <h2>Would you like to retract the ban against user <?php echo $user["username"]; ?>?</h2>
                <button href="#" class="btn secondary__btn secondary__btn-signup unban"
                    data-id="<?php echo $banId; ?>">Retract Ban</button>
            </div>
            <?php else: ?>
            <div class="banning">
                <h2>Would you like to ban user <?php echo $user["username"]; ?>?</h2>
                <button href="#" class="btn secondary__btn secondary__btn-signup ban"
                    data-id="<?php echo $banId; ?>">Ban user</button>
            </div>
            <div class="banned hidden">
                <h2>Would you like to retract the ban against user <?php echo $user["username"]; ?>?</h2>
                <button href="#" class="btn secondary__btn secondary__btn-signup unban"
                    data-id="<?php echo $banId; ?>">Retract Ban</button>
            </div>
            <?php endif; ?>






        </div>


        <div class="warnings">

            <form action="" method="post">

                <select name="id" id="">

                    <?php foreach($users as $usr): //behalve de moderators zelf !!! ?>
                    <option value="<?php echo $usr['id'];?>"><?php echo $usr['username'];?></option>
                    <?php endforeach; ?>



                </select>

                <div class="form__field" id="form__report__reason">
                    <label for="warning_reason" class="form__label">Reason</label>
                    <input type="warning_reason" name="warning_reason" class="form-control" id="warning_reason" required
                        placeholder="the reason for your report">
                </div>

                <button type="submit" class="btn secondary__btn secondary__btn-signup">SEnd</button>
            </form>
        </div>



    </main>
    <script src="./javascript/add_remove_ban.js"></script>
    <script src="./javascript/warning.js"></script>
</body>

</html>