<?php if(basename($_SERVER['PHP_SELF']) !== "index.php"): ?>
    <footer>
        <div class="footer__info">
            <img src="./assets/TM.svg" alt="Thomas More logo">
            <p>The platform d-zine was made by second year Interactive and Multimedia Design students at Thomas More University of Applied Sciences.</p>
        </div>
        <div class="footer__socials">
            <a href="mailto:" target="_blank">
                <img src="./assets/mail_icon.svg" alt="email icon">
            </a>
            <a href="https://www.facebook.com/WeAreIMD/" target="_blank">
                <img src="./assets/fb_icon.svg" alt="facebook icon">
            </a>
            <a href="https://www.instagram.com/imdvibes/?hl=en" target="_blank">
                <img src="./assets/insta_icon.svg" alt="instagram icon">
            </a>
            <a href="https://weareimd.be/" target="_blank">
                <img src="./assets/web_icon.svg" alt="website icon">
            </a>
        </div>
    </footer>
<?php else: ?>
    <footer class="footer--index">
        <div class="footer__info">
            <img src="./assets/TM.svg" alt="Thomas More logo">
            <p>The platform d-zine was made by second year Interactive and Multimedia Design students at Thomas More University of Applied Sciences.</p>
        </div>
        <div class="footer__socials">
            <a href="mailto:" target="_blank">
                <img src="./assets/mail_icon.svg" alt="email icon">
            </a>
            <a href="https://www.facebook.com/WeAreIMD/" target="_blank">
                <img src="./assets/fb_icon.svg" alt="facebook icon">
            </a>
            <a href="https://www.instagram.com/imdvibes/?hl=en" target="_blank">
                <img src="./assets/insta_icon.svg" alt="instagram icon">
            </a>
            <a href="https://weareimd.be/" target="_blank">
                <img src="./assets/web_icon.svg" alt="website icon">
            </a>
        </div>
    </footer>
<?php endif; ?>


