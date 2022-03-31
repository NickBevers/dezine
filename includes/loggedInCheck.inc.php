<?php
include_once("./helpers/Security.help.php");

if(!Security::isLoggedIn()) {
    header('Location: login.php');
}