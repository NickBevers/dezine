<?php
    include_once("bootstrap.php");
    use \Helpers\Validate;
    use \Helpers\Security;
    use \Helpers\Cleaner;
    use \Classes\Auth\User;

    Validate::start();

    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    $uid = Cleaner::cleanInput($_SESSION["id"]);
    if (!User::checkModerator($uid)) {
        header('Location: home.php');
    }

    $banId = Cleaner::cleanInput($_GET["id"]);
    $user = User::getUserbyId($banId);

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

            <?php if (User::checkBan($banId)): ?>
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
        </div>        
    </main>
    <script src="./javascript/add_remove_ban.js"></script>
</body>
</html>