<?php
$titolo_pagina = 'Rifugio Faggio Crociato';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO -->
    <section class="page-hero page-hero--rifugio">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Ristorante · Bar</p>
            <h1 class="page-hero-title">Rifugio Faggio Crociato</h1>
            <p class="hero-sub">Cucina lunigianese autentica, a 1400 metri sul mare</p>
        </div>
    </section>

    <!-- INTRO -->
    <section class="page-intro container">
        <div class="intro-text">
            <p class="section-label">Il rifugio</p>
            <h2 class="section-title">Sapori di montagna, ingredienti di territorio</h2>
            <p>Il Rifugio Faggio Crociato è il cuore gastronomico di Zum Zeri. La cucina propone piatti della tradizione lunigianese preparati in casa ogni giorno, con ingredienti freschi e di qualità selezionati dal territorio.</p>
            <p>Il rifugio offre anche servizio bar tutto il giorno. Su richiesta, il menù può essere adattato per allergie, intolleranze alimentari o preferenze vegetariane.</p>
            <a href="/zumzeri/prenota.php" class="btn-primary" style="display:inline-block; margin-top:20px;">Prenota un tavolo</a>
        </div>
        <div class="intro-dati">
            <div class="dato-item"><strong>Sab</strong><span>Pranzo e cena</span></div>
            <div class="dato-item"><strong>Dom</strong><span>Solo pranzo</span></div>
            <div class="dato-item"><strong>Bar</strong><span>Tutti i giorni</span></div>
            <div class="dato-item"><strong>km0</strong><span>Ingredienti locali</span></div>
        </div>
    </section>

    <!-- MENU -->
    <section class="section-menu">
        <div class="container">
            <p class="section-label">La cucina</p>
            <h2 class="section-title">Piatti della tradizione lunigianese</h2>
            <p class="section-sub">Tutto preparato in casa, con ingredienti freschi. Il menù cambia con le stagioni.</p>

            <div class="menu-grid">

                <div class="menu-categoria">
                    <h3 class="menu-cat-title">Antipasti</h3>
                    <ul class="menu-lista">
                        <li>
                            <span class="menu-piatto">Antipasto misto del territorio</span>
                            <span class="menu-desc">Formaggi locali, salumi e torte salate fatte in casa</span>
                        </li>
                    </ul>
                </div>

                <div class="menu-categoria">
                    <h3 class="menu-cat-title">Primi piatti</h3>
                    <ul class="menu-lista">
                        <li>
                            <span class="menu-piatto">Tagliatelle ai funghi</span>
                            <span class="menu-desc">Fatte a mano, con sugo di funghi di stagione</span>
                        </li>
                        <li>
                            <span class="menu-piatto">Ravioli al ragù</span>
                            <span class="menu-desc">Ragù fatto a mano</span>
                        </li>
                    </ul>
                </div>

                <div class="menu-categoria">
                    <h3 class="menu-cat-title">Secondi e contorni</h3>
                    <ul class="menu-lista">
                        <li>
                            <span class="menu-piatto">Agnello fritto</span>
                            <span class="menu-desc">Impanato e fritto al momento</span>
                        </li>
                        <li>
                            <span class="menu-piatto">Agnello al forno</span>
                            <span class="menu-desc">Cottura lenta per esaltarne il sapore</span>
                        </li>
                        <li>
                            <span class="menu-piatto">Contorni di verdura</span>
                            <span class="menu-desc">Verdure di stagione</span>
                        </li>
                    </ul>
                </div>

                <div class="menu-categoria">
                    <h3 class="menu-cat-title">Dolci</h3>
                    <ul class="menu-lista">
                        <li>
                            <span class="menu-piatto">Dolci fatti a mano</span>
                            <span class="menu-desc">Selezione variabile, preparati ogni giorno</span>
                        </li>
                    </ul>
                </div>

                <div class="menu-categoria">
                    <h3 class="menu-cat-title">Da bere</h3>
                    <ul class="menu-lista">
                        <li>
                            <span class="menu-piatto">Vini locali</span>
                            <span class="menu-desc">Selezione di vini del territorio</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="note-camere">
                <p>Il menù varia in base alla stagione e alla disponibilità degli ingredienti. Siamo disponibili per allergie, intolleranze e diete vegetariane — contattaci prima della prenotazione.</p>
                <a href="/zumzeri/contatti.php">Contattaci →</a>
            </div>
        </div>
    </section>

    <!-- ORARI -->
    <section class="section-orari container">
        <p class="section-label">Orari e modalità</p>
        <h2 class="section-title">Quando siamo aperti</h2>

        <div class="orari-grid">
            <div class="orario-card orario-card--estate">
                <h3 class="orario-stagione">Estate</h3>
                <div class="orario-row">
                    <span>Sabato</span>
                    <strong>Pranzo e cena</strong>
                </div>
                <div class="orario-row">
                    <span>Domenica</span>
                    <strong>Solo pranzo</strong>
                </div>
                <div class="orario-row">
                    <span>Bar</span>
                    <strong>Tutti i giorni</strong>
                </div>
                <p class="orario-note">Prenotazione consigliata per il sabato sera</p>
            </div>

            <div class="orario-card orario-card--inverno">
                <h3 class="orario-stagione">Inverno</h3>
                <div class="orario-row">
                    <span>Giorni di apertura impianti</span>
                    <strong>Self-service</strong>
                </div>
                <div class="orario-row">
                    <span>Sabato e domenica</span>
                    <strong>Pranzo e cena (su prenotazione)</strong>
                </div>
                <div class="orario-row">
                    <span>Bar</span>
                    <strong>Tutti i giorni</strong>
                </div>
                <p class="orario-note">In alta stagione sciistica il ristorante funziona in modalità self-service per snellire il servizio</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-prenota">
        <div class="container">
            <h2 class="cta-title">Vuoi prenotare un tavolo?</h2>
            <p class="cta-sub">Controlla la disponibilità e prenota direttamente online.</p>
            <a href="/zumzeri/prenota.php" class="btn-primary">Prenota ora</a>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>