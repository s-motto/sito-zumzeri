<?php
$titolo_pagina = 'Gran Baita Lunigiana';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO PAGINA -->
    <section class="page-hero page-hero--granbaita">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Hotel · 24 camere</p>
            <h1 class="page-hero-title">Gran Baita Lunigiana</h1>
            <p class="hero-sub">Immersa nella natura, a pochi passi dalle piste e dai sentieri</p>
        </div>
    </section>

    <!-- INTRO -->
    <section class="page-intro container">
        <div class="intro-text">
            <p class="section-label">La struttura</p>
            <h2 class="section-title">Un rifugio confortevole tra tre regioni</h2>
            <p>La Gran Baita Lunigiana si trova a Passo dei Due Santi, punto di incontro tra Toscana, Liguria ed Emilia-Romagna, a 1400 metri sul mare. La struttura offre 24 camere confortevoli con bagno privato, ideali per famiglie, gruppi e coppie che vogliono godersi la montagna senza rinunciare al comfort essenziale.</p>
            <p>In inverno è la base ideale per sciare sulle piste del comprensorio di Zum Zeri. In estate diventa il punto di partenza per escursioni, trekking e passeggiate nella natura incontaminata della Lunigiana.</p>
            <a href="/zumzeri/prenota.php" class="btn-primary" style="display:inline-block; margin-top:20px;">Prenota una camera</a>
        </div>
        <div class="intro-dati">
            <div class="dato-item"><strong>24</strong><span>camere disponibili</span></div>
            <div class="dato-item"><strong>1400</strong><span>metri s.l.m.</span></div>
            <div class="dato-item"><strong>3</strong><span>regioni di confine</span></div>
            <div class="dato-item"><strong>Wi-Fi</strong><span>gratuito</span></div>
        </div>
    </section>

    <!-- CAMERE -->
    <section class="section-camere">
        <div class="container">
            <p class="section-label">Le camere</p>
            <h2 class="section-title">Semplici, pulite, confortevoli</h2>
            <p class="section-sub">Le nostre camere sono arredate in stile montano, senza pretese ma con tutto il necessario. Ogni camera ha bagno privato.</p>

            <div class="camere-grid">
                <div class="camera-card">
                    <div class="camera-img camera-img--doppia"></div>
                    <div class="camera-body">
                        <h3 class="camera-tipo">Camera doppia</h3>
                        <p class="camera-desc">Ideale per coppie. Letto matrimoniale o due letti singoli, bagno privato.</p>
                        <ul class="camera-dotazioni">
                            <li>Bagno privato</li>
                            <li>Riscaldamento</li>
                            <li>Wi-Fi</li>
                        </ul>
                    </div>
                </div>
                <div class="camera-card">
                    <div class="camera-img camera-img--tripla"></div>
                    <div class="camera-body">
                        <h3 class="camera-tipo">Camera tripla</h3>
                        <p class="camera-desc">Per famiglie o piccoli gruppi. Tre posti letto, bagno privato.</p>
                        <ul class="camera-dotazioni">
                            <li>Bagno privato</li>
                            <li>Riscaldamento</li>
                            <li>Wi-Fi</li>
                        </ul>
                    </div>
                </div>
                <div class="camera-card">
                    <div class="camera-img camera-img--quadrupla"></div>
                    <div class="camera-body">
                        <h3 class="camera-tipo">Camera quadrupla</h3>
                        <p class="camera-desc">Per famiglie numerose o gruppi. Quattro posti letto, bagno privato.</p>
                        <ul class="camera-dotazioni">
                            <li>Bagno privato</li>
                            <li>Riscaldamento</li>
                            <li>Wi-Fi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="note-camere">
                <p>Le camere possono essere riarrangiate su richiesta in base alle esigenze del gruppo. Contattaci per disponibilità personalizzate.</p>
                <a href="/zumzeri/contatti.php">Contattaci →</a>
            </div>
        </div>
    </section>

    <!-- SERVIZI -->
    <section class="section-servizi-gb container">
        <p class="section-label">Servizi inclusi</p>
        <h2 class="section-title">Tutto quello che ti serve</h2>
        <div class="servizi-gb-grid">
            <div class="servizio-gb"><strong>Wi-Fi gratuito</strong>
                <p>In tutta la struttura</p>
            </div>
            <div class="servizio-gb"><strong>Colazione</strong>
                <p>Disponibile su richiesta</p>
            </div>
            <div class="servizio-gb"><strong>Pet friendly</strong>
                <p>Animali benvenuti</p>
            </div>
            <div class="servizio-gb"><strong>Barbecue</strong>
                <p>A disposizione degli ospiti</p>
            </div>
            <div class="servizio-gb"><strong>Giochi da tavolo</strong>
                <p>Ampia selezione disponibile</p>
            </div>
            <div class="servizio-gb"><strong>Skipass</strong>
                <p>Acquistabile in struttura</p>
            </div>
        </div>
    </section>

    <!-- CTA PRENOTA -->
    <section class="cta-prenota">
        <div class="container">
            <h2 class="cta-title">Pronto a prenotare?</h2>
            <p class="cta-sub">Verifica la disponibilità per le tue date e prenota direttamente online.</p>
            <a href="/zumzeri/prenota.php" class="btn-primary">Prenota ora</a>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>