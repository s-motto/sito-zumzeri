<?php
$titolo_pagina = 'Attività';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO -->
    <section class="page-hero page-hero--attivita">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Passo dei Due Santi · 1400 m</p>
            <h1 class="page-hero-title">Cosa fare a Zum Zeri</h1>
            <p class="hero-sub">Montagna, natura e sport in ogni stagione</p>
        </div>
    </section>

    <!-- STAGIONE SELECTOR -->
    <div class="stagione-selector">
        <button class="stagione-tab active" id="tab-estate" onclick="setStagione('estate')">
            <span class="stagione-tab-icon">&#9651;</span>
            Estate
        </button>
        <button class="stagione-tab" id="tab-inverno" onclick="setStagione('inverno')">
            <span class="stagione-tab-icon">&#10053;</span>
            Inverno
        </button>
    </div>

    <!-- ESTATE -->
    <div id="contenuto-estate">

        <section class="attivita-intro container">
            <p class="section-label">Estate</p>
            <h2 class="section-title">La montagna si tinge di verde</h2>
            <p class="section-sub">In estate il Passo dei Due Santi diventa un paradiso per chi ama la natura, il trekking e l'aria pulita di montagna. Paesaggi mozzafiato a cavallo tra tre regioni.</p>
        </section>

        <section class="attivita-grid container">
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--trekking"></div>
                <div class="attivita-card-body">
                    <h3>Trekking ed escursioni</h3>
                    <p>Sentieri per tutti i livelli tra Toscana, Liguria ed Emilia-Romagna. Nelle giornate limpide si vede il mar Ligure e la Corsica.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--mtb"></div>
                <div class="attivita-card-body">
                    <h3>Mountain bike</h3>
                    <p>Percorsi su sterrato e sentieri di montagna adatti a tutti i livelli di esperienza.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--gonfiabile"></div>
                <div class="attivita-card-body">
                    <h3>Gonfiabile per bambini</h3>
                    <p>Area giochi con gonfiabile per i più piccoli, per divertirsi in sicurezza all'aria aperta.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--natura"></div>
                <div class="attivita-card-body">
                    <h3>Relax nella natura</h3>
                    <p>Picnic, barbecue e giornate di riposo immersi nel verde della montagna.</p>
                </div>
            </div>
        </section>

    </div>

    <!-- INVERNO -->
    <div id="contenuto-inverno" style="display:none">

        <section class="attivita-intro container">
            <p class="section-label">Inverno</p>
            <h2 class="section-title">La neve trasforma tutto</h2>
            <p class="section-sub">In inverno Zum Zeri diventa una piccola stazione sciistica a misura di famiglia. 8 km di piste, snowpark e anello di fondo — con vista sul Golfo di La Spezia.</p>
        </section>

        <!-- STATO IMPIANTI -->
        <section class="stato-impianti container">
            <div class="impianti-header">
                <h3>Stato impianti</h3>
                <span class="impianti-data">Aggiornamento: <?= date('d/m/Y') ?></span>
            </div>
            <div class="impianti-grid">
                <div class="impianto-row">
                    <span class="impianto-nome">Campo scuola</span>
                    <span class="impianto-stato stato--chiuso">Chiuso</span>
                </div>
                <div class="impianto-row">
                    <span class="impianto-nome">Fabei</span>
                    <span class="impianto-stato stato--chiuso">Chiuso</span>
                </div>
                <div class="impianto-row">
                    <span class="impianto-nome">Cippo 15</span>
                    <span class="impianto-stato stato--chiuso">Chiuso</span>
                </div>
                <div class="impianto-row">
                    <span class="impianto-nome">Anello di fondo (12 km)</span>
                    <span class="impianto-stato stato--chiuso">Chiuso</span>
                </div>
            </div>
            <p class="impianti-note">Gli impianti sono operativi da dicembre a marzo, in base alle condizioni nevose. Segui i nostri canali social per aggiornamenti in tempo reale.</p>
        </section>

        <section class="attivita-grid container">
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--sci"></div>
                <div class="attivita-card-body">
                    <h3>Sci e snowboard</h3>
                    <p>8 km di piste facili e medie, adatte a famiglie e principianti. Vista sul Golfo di La Spezia nelle giornate limpide.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--scuola"></div>
                <div class="attivita-card-body">
                    <h3>Campo scuola</h3>
                    <p>Area dedicata ai principianti e ai bambini, ideale per imparare a sciare in sicurezza.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--snowpark"></div>
                <div class="attivita-card-body">
                    <h3>Snowpark</h3>
                    <p>750 metri di percorso attrezzato con kicker e rail per gli amanti del freestyle.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--fondo"></div>
                <div class="attivita-card-body">
                    <h3>Sci di fondo</h3>
                    <p>Anello di 12 km immerso nella natura, per tutti i livelli.</p>
                </div>
            </div>
            <div class="attivita-card">
                <div class="attivita-card-head attivita-card-head--ciaspole"></div>
                <div class="attivita-card-body">
                    <h3>Ciaspolate</h3>
                    <p>Escursioni con le racchette da neve tra i boschi del Passo dei Due Santi.</p>
                </div>
            </div>
        </section>

        <!-- SKIPASS E NOLEGGIO -->
        <section class="section-skipass container">
            <div class="skipass-grid">
                <div class="skipass-box">
                    <h3>Skipass</h3>
                    <p>Acquistabile direttamente presso il Rifugio Faggio Crociato. Tariffe giornaliere e settimanali disponibili.</p>
                    <a href="/zumzeri/contatti.php">Informazioni →</a>
                </div>
                <div class="skipass-box">
                    <h3>Noleggio attrezzature</h3>
                    <p>Sci, snowboard, scarponi e tutto il necessario disponibile presso Lucchi Sport in loco.</p>
                    <a href="/zumzeri/contatti.php">Informazioni →</a>
                </div>
            </div>
        </section>

    </div>

    <!-- CTA -->
    <section class="cta-prenota">
        <div class="container">
            <h2 class="cta-title">Prenota il tuo soggiorno</h2>
            <p class="cta-sub">Scegli le tue date e verifica la disponibilità delle camere.</p>
            <a href="/zumzeri/prenota.php" class="btn-primary">Prenota ora</a>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>

<script>
    function setStagione(s) {
        const isInv = s === 'inverno';
        document.getElementById('tab-estate').classList.toggle('active', !isInv);
        document.getElementById('tab-inverno').classList.toggle('active', isInv);
        document.getElementById('contenuto-estate').style.display = isInv ? 'none' : 'block';
        document.getElementById('contenuto-inverno').style.display = isInv ? 'block' : 'none';
    }
</script>