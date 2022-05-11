<?php
    include_once(__DIR__ . "/autoloader.php");
    include_once(__DIR__ . "/helpers/Security.help.php");
    include_once(__DIR__ . "/helpers/CheckEmpty.help.php");
	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }
    $email = $_SESSION['email'];
    $user = new User();
    $user->setEmail($email);
    $users = $user->getUser();
    
    if (!empty($_POST)) {
        $username = $_POST['username'];
        $education = $_POST['education'];
        $bio = $_POST['bio'];
        $linkedin = $_POST['linkedin'];
        $website = $_POST['website'];
        $instagram = $_POST['instagram'];
        $github = $_POST['github'];
        $second_email = $_POST['second_email'];
        
        try{
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setEducation($education);
            $user->setBio($bio);
            $user->setLinkedin($linkedin);
            $user->setWebsite($website);
            $user->setInstagram($instagram);
            $user->setGithub($github);
            $user->setSecondEmail($second_email);

            $default_image = "assets/default_profile_image.png";

            $fileName = basename($_FILES["profile_image"]["name"]);
            $fileName = str_replace(" ", "_", $fileName);
            $targetFilePath = "uploads/profile/" . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedFileTypes = array('jpg','png','jpeg','gif', 'jfif', 'webp');    
           
            if (isset($_POST['checkbox_name'])){
                $user->setProfileImage($default_image);
            } else {
                if ($_FILES['profile_image']['size'] == 0){
                    // cover_image is empty (and not an error)
                    $profile_image = $users["profile_image"];
                    $user->setProfileImage($profile_image);
                } else {
                    if(in_array($fileType, $allowedFileTypes)){
                        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)){
                            $user->setProfileImage($targetFilePath);
                        } else {
                            throw new Exception("The image could not be saved, please try again");
                        }
                    } else {
                        throw new Exception("Only this jpg','png','jpeg','gif', 'jfif', 'webp images allowed");
                    }
                }
            }

            $users = $user->updateUser();
            if($users){
                // header("Refresh:0");
                $success = "Your profile was successfully updated";
            } else{
                $error = "Something has gone wrong, please try again.";
            }
        } catch (Throwable $error) {
                $error = $error->getMessage();
        }
    }
?>
<!DOCTYPE html>
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

        <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> enctype='multipart/form-data' class="form form--profile">
            <h2>Update Profile</h2>
            <div class="form__field form__field-image">
                <div class="form__field">
                    <img src=<?php echo $users["profile_image"] ?> class="form__image">              
                </div>  
                <div class="form__field form__image-cb">
                        <input type="checkbox" name="checkbox_name" value="checkbox_value" id="cb">
                        <label for="cb" id="label_cb"><img src="assets/default_profile_image.png"/></label>
                    </div>
            </div>
            <div class="form__field">
                <label for="profile_image" class="btn secondary__btn-reverse">Edit profile picture
                    <input type="file" name="profile_image" class="form-control" id="profile_image">
                </label>
            </div>

            <div class="form__field">
                <label for="username" class="form-label">Username</label>
                <input type="username" name="username" class="form-control" id="username" required
                    value="<?php echo $users["username"]; ?>">
            </div>

            <div class="form__field">
                <label for="education" class="form-label">Education</label>
                <input type="education" name="education" class="form-control" id="education"
                    value="<?php echo $users["education"]; ?>">
            </div>

            <div class="form__field">
                <label for="bio" class="form-label">Bio</label>
                <textarea type="bio" name="bio" class="form-control" id="bio" cols="60"
                    row="30"><?php echo $users["bio"]; ?></textarea>
            </div>

            <h4>Update Socials</h4>
            <div class="form__field">
                <label for="linkedin" class="form-label">Linkedin</label>
                <input type="linkedin" name="linkedin" class="form-control" id="linkedin"
                    value="<?php echo $users["linkedin"]; ?>">
            </div>

            <div class="form__field">
                <label for="website" class="form-label">Website</label>
                <input type="website" name="website" class="form-control" id="website"
                    value="<?php echo $users["website"]; ?>">
            </div>

            <div class="form__field">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="instagram" name="instagram" class="form-control" id="instagram"
                    value="<?php echo $users["instagram"]; ?>">
            </div>

            <div class="form__field">
                <label for="github" class="form-label">Github</label>
                <input type="github" name="github" class="form-control" id="github"
                    value="<?php echo $users["github"]; ?>">
            </div>

            <h4>Update Second Email</h4>
            <div class="form__field">
                <label for="second_email" class="form-label">Second_email</label>
                <input type="second_email" name="second_email" class="form-control" id="second_email"
                    value="<?php echo $users["second_email"]; ?>">
            </div>

            <div class="form__field form__field-btn">
                <button type="submit" class="btn secondary__btn secondary__btn-signup">Update</button>
                <a href="profile_password.php" class="btn secondary__btn-reverse secondary__btn-signup">Change password</a>
                <a href="./userDelete.php" class="btn secondary__btn secondary__btn-signup">Delete account</a>
            </div>
        </form>
    </main>
</body>

</html>