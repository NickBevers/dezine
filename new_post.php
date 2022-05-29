<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Security;
    use Dezine\Auth\User;
    use Dezine\Content\Post;
    use Dezine\Content\UploadImage;

    Validate::start();

    if (!Security::isLoggedIn()) {
        header('Location: login.php');
    } else if (User::checkban($_SESSION["id"])) {
        header('Location: home.php');
    }
    if (!empty($_POST)) {
        $title = $_POST["title"];
        $description = $_POST["description"];
        $tags = $_POST["tags"];
        $user_id = $_SESSION["id"];

        try {
            $image = UploadImage::getImageData($_FILES["image"]["name"], $_FILES["image"]["tmp_name"], $user_id);
            $uploadedFile = UploadImage::uploadPostPic($image);
            $project = new Post();
            $project->setTitle($title);
            $project->setDescription($description);
            $project->setTags($tags);                       
            $project->setPublic_id($uploadedFile["public_id"]);
            $project->setImage($uploadedFile["secure_url"]);
            $project->setColors($image);
            if($uploadedFile){unlink($image);}
            if ($project->addPost($user_id)) {
                header("Location: home.php");
            } else {
                $error = "Something has gone wrong, please try again.";
            }
        } catch (Throwable $error) {
            $error = $error->getMessage();
        }
    } 
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dezine</title>    
    <link rel="stylesheet" href="./styles/style.css">    
    <link rel="stylesheet" href="https://use.typekit.net/nkx6euf.css">
</head>
<body class="container">
  <?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>
  <main>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" enctype='multipart/form-data' class="form form--register">
      <h2>New Post</h2>

      <div class="form__field">
        <label for="image" class="btn secondary__btn-reverse">Add image
          <input type="file" name="image" class="form__image-input" id="image">
        </label>
      </div>

      <div class="form__field">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" id="title" aria-describedby="postTitle">
      </div>

      <div class="form__field">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" name="description" class="form-control" id="description" maxlength="250" style="resize: none;"></textarea>
      </div>

      <div class="form__field">
        <label for="tags" class="form-label">Tags</label>
        <input type="text" name="tags" class="form-control" id="tags">
        <div id="passwordHelp" class="form-text">Separate multiple tags with a comma between them</div>
      </div>

      <div class="form__field">
        <button type="submit" class="btn secondary__btn secondary__btn-signup">Create new post</button>        
      </div>
      
    </form> 
  </main>
</body>
</html>