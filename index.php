<!DOCTYPE html>
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
<nav>
    <div class="nav__full">
        <div class="nav__info">
            <a href="">About</a>
            <a href="">Explore</a>
        </div>
        <div class="nav__logo">
            <a href="index.php">
                <img src="./assets/imd.svg" alt="IMD logo">
            </a>
        </div>
        <div class="nav__link">
            <a href="login.php">Login</a>
            <a href="signup.php">Sign up</a>
            <a href="">contact</a>
        </div>
    </div>
    <div class="nav__mobile">
        <div class="nav__logo">
            <a href="index.php">
                <img src="./assets/imd.svg" alt="IMD logo">
            </a>
        </div>
        <div class="nav_mobile_menu">
            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn">
                <span class="navicon"></span>
            </label>
            <div class="nav__link">
                <a href="">About</a>
                <a href="">Explore</a>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign up</a>
                <a href="">contact</a>
            </div>
        </div>        
    </div>
        
    </nav>

    <section class="bkg">
        <img src="./assets/dezine.svg" alt="Dezine logo">
    </section>
    
    <?php include_once("./includes/footer.inc.php"); ?>
</body>
</html>