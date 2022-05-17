<?php
    include_once(__DIR__ . "./bootstrap.php");
    use \Helpers\Validate;

    Validate::start();
    Validate::end();
    header("Location: index.php");
