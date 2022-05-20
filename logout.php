<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Validate;

    Validate::start();
    Validate::end();
    header("Location: index.php");
