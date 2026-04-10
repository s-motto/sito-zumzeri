<?php
$pagina_corrente = basename($_SERVER['PHP_SELF'], '.php');
$site_url = 'https://www.zumzeri.it';

// Valori di default — ogni pagina può sovrascriverli
$meta_titolo      = $titolo_pagina ?? 'Zum Zeri';
$meta_descrizione = $meta_descrizione ?? 'Zum Zeri — Rifugio e hotel a Passo dei Due Santi, Zeri (MS). Sci, trekking, cucina lunigianese. Prenota camere e tavoli online.';
$meta_url         = $site_url . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($meta_titolo) ?> — Zum Zeri · Passo dei Due Santi</title>
    <meta name="description" content="<?= htmlspecialchars($meta_descrizione) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($meta_url) ?>">

    <!-- Open Graph (Facebook, WhatsApp, ecc.) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($meta_titolo) ?> — Zum Zeri">
    <meta property="og:description" content="<?= htmlspecialchars($meta_descrizione) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($meta_url) ?>">
    <meta property="og:site_name" content="Zum Zeri">
    <meta property="og:locale" content="it_IT">

    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
</head>

<body>

    <header class="site-header">
        <div class="header-inner">
            <a href="/zumzeri/index.php" class="logo">Zum Zeri</a>
            <nav class="main-nav">
                <a href="/zumzeri/gran-baita.php" class="<?= $pagina_corrente === 'gran-baita' ? 'active' : '' ?>">Gran Baita</a>
                <a href="/zumzeri/rifugio.php" class="<?= $pagina_corrente === 'rifugio' ? 'active' : '' ?>">Rifugio</a>
                <a href="/zumzeri/attivita.php" class="<?= $pagina_corrente === 'attivita' ? 'active' : '' ?>">Attività</a>
                <a href="/zumzeri/webcam.php" class="<?= $pagina_corrente === 'webcam' ? 'active' : '' ?>">Webcam</a>
                <a href="/zumzeri/contatti.php" class="<?= $pagina_corrente === 'contatti' ? 'active' : '' ?>">Contatti</a>
                <a href="/zumzeri/prenota.php" class="btn-prenota <?= $pagina_corrente === 'prenota' ? 'active' : '' ?>">Prenota</a>
            </nav>
        </div>
    </header>