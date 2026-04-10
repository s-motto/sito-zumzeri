<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col">
            <span class="footer-logo">Zum Zeri</span>
            <p>Passo dei Due Santi, Zeri (MS)<br>
                Lunigiana — 1400 m s.l.m.</p>
        </div>
        <div class="footer-col">
            <strong>Strutture</strong>
            <a href="/zumzeri/gran-baita.php">Gran Baita Lunigiana</a>
            <a href="/zumzeri/rifugio.php">Rifugio Faggio Crociato</a>
        </div>
        <div class="footer-col">
            <strong>Contatti</strong>
            <a href="tel:+39XXXXXXXXXX">+39 XXX XXX XXXX</a>
            <a href="mailto:info@zumzeri.it">info@zumzeri.it</a>
        </div>
        <div class="footer-col">
            <strong>Seguici</strong>
            <a href="https://www.facebook.com/zumzerieu/" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/zum_zeri/" target="_blank">Instagram</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> Zum Zeri · Passo dei Due Santi</span>
        <a href="/zumzeri/privacy.php">Privacy Policy</a>
    </div>
</footer>
<!-- COOKIE BANNER -->
<div id="cookie-banner" class="cookie-banner" style="display:none;">
    <div class="cookie-banner-inner">
        <p>Questo sito utilizza solo cookie tecnici necessari al funzionamento. Non raccogliamo dati a fini pubblicitari. <a href="/zumzeri/privacy.php">Privacy Policy</a></p>
        <button onclick="accettaCookie()" class="cookie-btn">Ho capito</button>
    </div>
</div>

<script>
    function accettaCookie() {
        localStorage.setItem('cookie_accettati', '1');
        document.getElementById('cookie-banner').style.display = 'none';
    }

    if (!localStorage.getItem('cookie_accettati')) {
        document.getElementById('cookie-banner').style.display = 'flex';
    }
</script>
</body>

</html>