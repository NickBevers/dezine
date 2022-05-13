<?php 
    include_once("./helpers/Validate.help.php");
    Validate::start();
    Validate::end();
    header("Location: index.php");
?>