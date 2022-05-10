<?php include_once("./helpers/Security.help.php"); ?>
<nav>
  <div class="nav--full">
    <div class="nav__element nav--left">
      <li>
        <?php if (Security::isLoggedIn()): ?>
          <a aria-current="page" href="home.php">
            Home
          </a>
        <?php endif; ?>
        <?php if (!Security::isLoggedIn()): ?>
          <a aria-current="page" href="index.php">
            Explore
          </a>
        <?php endif; ?>
        
      </li>
    </div>
    
    <div class="nav__element nav--center">
        <?php if ($_SERVER['REQUEST_URI'] !== "/php/dezine/index.php"): ?>
          <a href="home.php">
            <img src="./assets/dezine.svg" alt="D-zine logo">
          </a>
        <?php else: ?>
          <a href="index.php">
            <img src="./assets/imd.svg" alt="IMD logo">
          </a>
        <?php endif; ?>

    </div>

    <div class="nav__element nav--right">

      <?php if (!Security::isLoggedIn()): ?>
        <a href="login.php" class="nav__button">Log in</a>
        <a href="register.php" class="nav__button">Register</a>
      <?php endif; ?>

      <?php if ($_SERVER['REQUEST_URI'] == "/php/dezine/index.php"): ?>
        <a href="contact.php" class="nav__button">Contact</a>
      <?php endif; ?>

      <?php if (Security::isLoggedIn()): ?>
        <a href="new_post.php">+ Add post</a>
      <?php endif; ?>

      <?php if (Security::isLoggedIn()): ?>
        <a href="profile.php" class="nav__button">Profile</a>
      <?php endif; ?>

      <?php if (!empty($_GET["id"]) && $_GET["id"] == $_SESSION["id"]): ?>
        <a class="nav-link" href="profile_user.php">Edit profile</a>
      <?php endif; ?>
      
      <?php if (Security::isLoggedIn()): ?>
        <a href="logout.php" class="nav__button">Log out</a>
      <?php endif; ?>

    </div>
  </div>

  <div class="nav--mobile">
    <div class="nav--center">
      <a href="index.php">
        <?php if ($_SERVER['REQUEST_URI'] !== "/php/dezine/index.php"): ?>
          <img src="./assets/dezine.svg" alt="D-zine logo">
        <?php else: ?>
          <img src="./assets/imd.svg" alt="IMD logo">
        <?php endif; ?>

      </a>
      </div>
      <div class="nav--mobile_menu">
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn">
          <span class="navicon"></span>
        </label>
        <div class="nav--right">

          <a aria-current="page" href="home.php">
            <?php if (Security::isLoggedIn()): ?>
              Home
            <?php endif; ?>
            <?php if (!Security::isLoggedIn()): ?>
              Explore
            <?php endif; ?>
          </a>
          
          <?php if (!Security::isLoggedIn()): ?>
            <a href="login.php" class="nav__button">Log in</a>
            <a href="register.php" class="nav__button">Register</a>
          <?php endif; ?>

          <?php if ($_SERVER['REQUEST_URI'] == "/php/dezine/index.php"): ?>
            <a href="contact.php" class="nav__button">Contact</a>
          <?php endif; ?>

          <?php if (Security::isLoggedIn()): ?>
            <a href="new_post.php">+ Add post</a>
          <?php endif; ?>

          <?php if (Security::isLoggedIn()): ?>
            <a href="profile.php" class="nav__button">Profile</a>
          <?php endif; ?>

          <?php if (!empty($_GET["id"]) && $_GET["id"] == $_SESSION["id"]): ?>
            <a class="nav-link" href="profile_user.php">Edit profile</a>
          <?php endif; ?>
          
          <?php if (Security::isLoggedIn()): ?>
            <a href="logout.php" class="nav__button">Log out</a>
          <?php endif; ?>

        </div>
      </div>       
  </div>
</nav>