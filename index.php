<?php  
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Content\Post;
    use Dezine\Helpers\Cleaner;

    $posts = POST::getSomePosts("desc", 0, 6);
    $posts = Cleaner::xss($posts);
    
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/orj4spc.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Dezine</title>
</head>
<body>
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>

    <section class="bkg">
        <img src="./assets/imd_dezine.svg" alt="Dezine logo">
    </section>

    <section class="banner">
        <div class="banner__loop">
            <h1 class="banner__loop__text">&nbsp; Showcase of our beautiful work - Showcase of our beautiful work - Showcase of our beautiful work -</h1>
            <h1 class="banner__loop__text2">&nbsp; Showcase of our beautiful work - Showcase of our beautiful work - Showcase of our beautiful work -</h1>
        </div>
    </section>

    <section class="posts">
    <?php foreach($posts as $post): ?>
        <div class="post post--index">
            <img src="<?php echo $post["image"] ?>" alt="<?php echo $post["title"] ?>" class="post--index__image">
        </div>              
    <?php endforeach; ?>
    </section>
    
    <?php include_once("./includes/footer.inc.php"); ?>
</body>
</html>