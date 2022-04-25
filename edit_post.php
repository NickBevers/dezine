<?php 
    
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");

	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    if(isset($_GET["p"])){
        if($_SESSION["id"] != $_GET["u"]) {
            header('Location: profile.php');
        }

        $post = POST::getPostByPostId($_GET["p"]);
    } else{
        header("Location: profile.php");
    }

    if(!empty($_POST)){
        try {
            $post = new Post();
            $post->setTitle($_POST["title"]);
            $post->setDescription($_POST["description"]);
            $post->setTags($_POST["tags"]);
            $post->updatePostById($_GET["p"]);

            header("Location: profile.php");
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Something went wrong, try again later.";
        }
    }
    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body class="container">
    <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>

    <main>
        <?php if(isset($_SESSION['flash_error'])): ?>
            <div class="error">
                <p><?php echo($_SESSION['flash_error']); ?></p>
            </div>
        <?php 
            unset($_SESSION['flash_error']);
            endif;
        ?>
        <form method="post" enctype='multipart/form-data'>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" id="title" aria-describedby="postTitle" value="<?php echo(htmlspecialchars($post["title"])); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea type="text" name="description" class="form-control" id="description" maxlength="250" required
                    style="resize: none;"><?php echo(htmlspecialchars($post["description"])); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <input type="text" name="tags" class="form-control" id="tags" value="<?php echo(implode(", ", json_decode($post["tags"]))); ?>" required>
                <div id="passwordHelp" class="form-text">Separate multiple tags with a comma between them</div>
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>
            <a href="profile.php">Cancel</a>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>

</html>