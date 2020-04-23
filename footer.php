</div>

<?php
    include 'search.php';
    include 'contact.php';
    $date = date('F');
    if ($date == 'December'){
        include 'svg/xmas-snow.php';
    }
?>

<footer>

    <div id="footer-container">
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"><span id="footer-copy"><span>&copy;</span><span>Dan Hart</span><span><?php echo date("Y"); ?></span></span></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
        <div class="footer-hex"></div>
    </div>

    <?php wp_footer(); ?>

</footer>

</body>

</html>