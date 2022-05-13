<?php 
    use \Helpers\Validate;
    Validate::start();
    Validate::end();
    header("Location: index.php");
?>