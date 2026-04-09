<?php
$pagina_corrente = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titolo_pagina ?? 'Zum Zeri' ?> — Passo dei Due Santi</title>
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