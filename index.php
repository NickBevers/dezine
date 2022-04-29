<?php  
    include_once(__DIR__ . "/autoloader.php");

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://use.typekit.net/orj4spc.css">
    <title>Dezine</title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>

    <section class="bkg">
        <img src="./assets/imd_dezine.svg" alt="Dezine logo">
    </section>

    <section class="banner">
        <div class="banner__loop">
            <h1 class="banner__loop__text">&nbsp; Showcase of our beautiful work - Showcase of our beautiful work</h1>
            <h1 class="banner__loop__text2">&nbsp; Showcase of our beautiful work - Showcase of our beautiful work</h1>
        </div>
    </section>

    <section class="posts">
    <?php $posts = POST::getSomePosts(0, 6); ?>
    <?php foreach($posts as $post): ?>
        <div class="post post--index">
            <img src=<?php echo $post["image"] ?> alt=<?php echo $post["title"] ?>>
            <div class="post__info">
                <h3><?php echo $post["title"] ?></h3>
                <p><?php echo $post["description"] ?></p>
                <?php $tags = json_decode($post["tags"]); ?>
                <div class="post__info__tags">
                    <?php foreach($tags as $t): ?>
                        <p><?php echo "#"; echo $t; echo "&nbsp"; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>              
    <?php endforeach; ?>
    </section>
    
    <?php include_once("./includes/footer.inc.php"); ?>
</body>
</html>