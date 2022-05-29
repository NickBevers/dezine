<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Dezine\Helpers\Validate;
    use \Dezine\Helpers\Security;
    use \Dezine\Content\Post;
    use \Dezine\Auth\User;
    use \Dezine\Helpers\Cleaner;

    Validate::start();

    if (!Security::isLoggedIn()) {header('Location: login.php');}
    if (User::checkban($_SESSION["id"])) {header('Location: home.php');}

    if (isset($_GET["pid"])) {
        if ($_SESSION["id"] != $_GET["uid"]) {
            header('Location: profile.php');
        }
        $post = Post::getPostByPostId($_GET["pid"]);
    } else {
        header("Location: profile.php");
    }

    if (!empty($_POST)) {
        try {
            $post = new Post();
            $post->setTitle($_POST["title"]);
            $post->setDescription($_POST["description"]);
            $post->setTags($_POST["tags"]);
            $post->updatePostById($_GET["pid"]);
            // header("Location: profile.php");
        } catch (Exception $e) {
            $error = $e->getMessage();        
            // $_SESSION['flash_error'] = "Something went wrong, try again later.";
        }        
    } 
    $post = $post = Post::getPostByPostId($_GET["pid"]);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit post</title>
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
</head>
<body class="container">
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
    <main>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger">
                <p><?php echo($_SESSION['flash_error']); ?></p>
            </div>
        <?php
            unset($_SESSION['flash_error']);
            endif;
        ?>
        
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" enctype='multipart/form-data' class="form form--register">
            <h2>Edit post</h2>
            <div class="form__field">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" id="title" aria-describedby="postTitle" value="<?php echo(Cleaner::xss(($post["title"]))); ?>">
            </div>

            <div class="form__field">
                <label for="description" class="form-label">Description</label>
                <textarea type="text" name="description" class="form-control" id="description" maxlength="250"
                    style="resize: none;"><?php echo(Cleaner::xss(($post["description"]))); ?></textarea>
            </div>

            <div class="form__field">
                <label for="tags" class="form-label">Tags</label>
                <input type="text" name="tags" class="form-control" id="tags" value="<?php echo(implode(", ", json_decode($post["tags"]))); ?>">
                <div id="passwordHelp" class="form-text">Separate multiple tags with a comma between them</div>
            </div>

            <div class="form__submit form__field">
                <button type="submit" class="btn secondary__btn secondary__btn-signup">Save changes</button>
                <a href="profile.php" class="btn secondary__btn-reverse secondary__btn-signup">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>