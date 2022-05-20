<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Helpers\Validate;

    Validate::start();
    Validate::end();
    header("Location: index.php");
