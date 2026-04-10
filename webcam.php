<?php
$titolo_pagina = 'Webcam';
$meta_descrizione = 'Webcam live di Zum Zeri: guarda il Passo dei Due Santi in tempo reale prima di partire.';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO -->
    <section class="page-hero page-hero--attivita">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Passo dei Due Santi · Live</p>
            <h1 class="page-hero-title">Webcam</h1>
            <p class="hero-sub">Guarda il passo in tempo reale prima di partire</p>
        </div>
    </section>

    <!-- WEBCAM -->
    <section class="section-webcam container">
        <p class="section-label">Diretta live</p>
        <h2 class="section-title">Due punti di vista sul passo</h2>
        <p class="section-sub">Le riprese sono in diretta — clicca play per guardare.</p>

        <div class="webcam-grid webcam-grid--due">

            <div class="webcam-card">
                <div class="webcam-embed">
                    <iframe
                        src="https://www.youtube.com/embed/wxAjfmnXju4"
                        title="Webcam Zum Zeri — Campo scuola"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="webcam-info">
                    <h3>Campo scuola</h3>
                    <p>Vista sul campo scuola per bambini — Rifugio Faggio Crociato</p>
                    <a href="https://www.youtube.com/live/wxAjfmnXju4" target="_blank" class="webcam-link">Apri su YouTube →</a>
                </div>
            </div>

            <div class="webcam-card">
                <div class="webcam-embed">
                    <iframe
                        src="https://www.youtube.com/embed/muDBFX3KbB4"
                        title="Webcam Zum Zeri — Piazzale del passo"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="webcam-info">
                    <h3>Piazzale del passo</h3>
                    <p>Vista sul piazzale del Passo dei Due Santi — Gran Baita Lunigiana</p>
                    <a href="https://www.youtube.com/live/muDBFX3KbB4" target="_blank" class="webcam-link">Apri su YouTube →</a>
                </div>
            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>