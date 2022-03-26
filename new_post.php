<?php 
    // include_once(__DIR__ . "/autoloader/bootstrap.php");
    include_once(__DIR__ . "/classes/Post.php");

    if(!empty($_POST)){
        $title = $_POST["title"];
        $description = $_POST["description"];
        $tags = $_POST["tags"];
        $image = $_POST["image"];

        try {
            $project = new Post();
            $project->setTitle($title);
            $project->setDescription($description);
            $project->setTags($tags);
            $project->setImage($image);
            $project->addPost();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body class="container">
<?php include_once(__DIR__ . "/includes/nav.inc.php"); ?>

<main>
<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
  <div class="mb-3">
    <label for="title" class="form-label">Tile</label>
    <input type="text" name="title" class="form-control" id="title" aria-describedby="postTitle" required>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <input type="text" name="description" class="form-control" id="description" required>
  </div>

  <div class="mb-3">
    <label for="tags" class="form-label">Tags</label>
    <input type="text" name="tags" class="form-control" id="tags" required>
    <div id="passwordHelp" class="form-text">Separate multiple tags with a comma between them</div>
  </div>

  <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" name="image" class="form-control" id="image" required>
  </div>
  
  <button type="submit" class="btn btn-primary">Create new post</button>
</form>


</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>