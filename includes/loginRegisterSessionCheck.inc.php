<?php
    include_once("bootstrap.php");
    use \Helpers\Security;

    if(Security::isLoggedIn()) {
        header('Location: home.php');
    }