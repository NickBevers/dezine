<?php
    include_once(__DIR__ . "/bootstrap.php");
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/Validate.help.php");
    use \Classes\Content\Post;
    use \Classes\Auth\User;
    Validate::start();

	  if(!Security::isLoggedIn()) {
      header('Location: login.php');
  }
  if(User::checkban($_SESSION["id"])){
    header('Location: home.php');
  }
    if(!empty($_POST)){
        $title = $_POST["title"];
        $description = $_POST["description"];
        $tags = $_POST["tags"];
        $user_id = $_SESSION["id"];

        try {
          $fileName = basename($_FILES["image"]["name"]);
          $fileName = str_replace(" ", "_", $fileName);
          $targetFilePath = "uploads/" . $user_id . $fileName;
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
          $allowedFileTypes = array('jpg','png','jpeg','gif', 'jfif', 'webp');

          if(!empty($_FILES["image"]["name"]) && in_array($fileType, $allowedFileTypes)){
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)){
              $project = new Post();
              $project->setTitle($title);
              $project->setDescription($description);
              $project->setTags($tags);
              $project->setImage($targetFilePath);
              $project->setColors();
              if($project->addPost($user_id)){
                header("Location: home.php");
              } else{
                $error = "Something has gone wrong, please try again.";
              }
            } else{
              $error = "The image could not be saved, please try again";
            }
          } else{
            $error = "Please choose an image for the project.";
          }
        } catch (\Throwable $error) {
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
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" enctype='multipart/form-data' class="form form--register">
      <h2>New Post</h2>
      <div class="form__field">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" id="title" aria-describedby="postTitle" required>
      </div>

      <div class="form__field">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" name="description" class="form-control" id="description" maxlength="250" required style="resize: none;"></textarea>
      </div>

      <div class="form__field">
        <label for="tags" class="form-label">Tags</label>
        <input type="text" name="tags" class="form-control" id="tags" required>
        <div id="passwordHelp" class="form-text">Separate multiple tags with a comma between them</div>
      </div>

      <div class="form__field">
        <label for="image" class="btn secondary__btn-reverse">Add image
          <input type="file" name="image" class="form__image-input" id="image" required>
        </label>
      </div>
      
      <button type="submit" class="btn secondary__btn secondary__btn-signup">Create new post</button>
    </form> 
  </main>
</body>
</html>