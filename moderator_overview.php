<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");
    if(!Security::isLoggedIn()) { header('Location: login.php');}

    $uid = Cleaner::cleanInput($_SESSION["id"]);
    if(!User::checkModerator($uid)){
        header('Location: home.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Moderator overview</title>
</head>
<body>
    <h1>Moderator Overviewpage</h1>
</body>
</html>