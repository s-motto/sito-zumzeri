<?php
$titolo_pagina = 'Home';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO -->
    <section class="hero" id="hero">
        <div class="hero-bg" id="hero-bg"></div>
        <div class="hero-overlay"></div>

        <div class="season-toggle">
            <button class="season-btn active" id="btn-estate" onclick="setSeason('estate')">Estate</button>
            <button class="season-btn" id="btn-inverno" onclick="setSeason('inverno')">Inverno</button>
        </div>

        <div class="hero-content container">
            <p class="hero-eyebrow">Passo dei Due Santi · Lunigiana</p>
            <h1 class="hero-title" id="hero-title">Tra montagna,<br>cielo e mare</h1>
            <p class="hero-sub" id="hero-sub">Trekking, natura e cucina lunigianese a 1400 m s.l.m.</p>
            <div class="hero-ctas">
                <a href="/zumzeri/prenota.php" class="btn-primary">Prenota una camera</a>
                <a href="/zumzeri/attivita.php" class="btn-outline">Scopri le attività</a>
            </div>
        </div>
    </section>

    <!-- BARRA INFO -->
    <div class="info-bar">
        <div class="info-item"><span>Quota</span><strong>1.400 m</strong></div>
        <div class="info-item"><span>Strutture</span><strong>2 rifugi</strong></div>
        <div class="info-item"><span>Camere</span><strong>24 camere</strong></div>
        <div class="info-item"><span>Stagione</span><strong id="stagione-label">Estate 2025</strong></div>
    </div>

    <!-- STRUTTURE -->
    <section class="section-strutture container">
        <p class="section-label">Le nostre strutture</p>
        <h2 class="section-title">Due rifugi, un solo posto</h2>
        <p class="section-sub">Il Faggio Crociato e la Gran Baita Lunigiana — a pochi passi l'uno dall'altro, con carattere diverso per ogni esigenza.</p>

        <div class="cards-strutture">
            <a href="/zumzeri/rifugio.php" class="card-struttura">
                <div class="card-img card-img--rifugio"></div>
                <div class="card-body">
                    <p class="card-label">Ristorante · Bar</p>
                    <h3 class="card-title">Rifugio Faggio Crociato</h3>
                    <p class="card-text">Cucina lunigianese, piatti fatti in casa con ingredienti freschi. Aperto il fine settimana a pranzo e cena.</p>
                    <span class="card-link">Scopri →</span>
                </div>
            </a>
            <a href="/zumzeri/gran-baita.php" class="card-struttura">
                <div class="card-img card-img--granbaita"></div>
                <div class="card-body">
                    <p class="card-label">Hotel · 24 camere</p>
                    <h3 class="card-title">Gran Baita Lunigiana</h3>
                    <p class="card-text">Camere confortevoli con bagno privato, spazio comune, wi-fi gratuito. Ideale per famiglie e gruppi.</p>
                    <span class="card-link">Scopri →</span>
                </div>
            </a>
        </div>
    </section>

    <!-- SEZIONE PRENOTA -->
    <section class="section-prenota">
        <div class="container">
            <h2 class="prenota-title">Prenota</h2>
            <div class="prenota-tabs">
                <button class="prenota-tab active" id="tab-camere" onclick="setTab('camere')">Camere</button>
                <button class="prenota-tab" id="tab-tavoli" onclick="setTab('tavoli')">Ristorante</button>
            </div>

            <form method="GET" action="/zumzeri/prenota.php" id="form-camere" class="prenota-form">
                <div class="form-field">
                    <label>Arrivo</label>
                    <input type="date" name="check_in">
                </div>
                <div class="form-field">
                    <label>Partenza</label>
                    <input type="date" name="check_out">
                </div>
                <div class="form-field">
                    <label>Ospiti</label>
                    <select name="ospiti">
                        <option value="1">1 ospite</option>
                        <option value="2" selected>2 ospiti</option>
                        <option value="3">3 ospiti</option>
                        <option value="4">4 ospiti</option>
                        <option value="5">5 ospiti</option>
                        <option value="6">6 ospiti</option>
                    </select>
                </div>
                <button type="submit" class="btn-prenota-form">Verifica disponibilità</button>
            </form>

            <form method="GET" action="/zumzeri/prenota-ristorante.php" id="form-tavoli" class="prenota-form" style="display:none">
                <div class="form-field">
                    <label>Data</label>
                    <input type="date" name="data">
                </div>
                <div class="form-field">
                    <label>Turno</label>
                    <select name="turno">
                        <option value="pranzo">Pranzo</option>
                        <option value="cena">Cena</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Persone</label>
                    <select name="persone">
                        <option value="1">1 persona</option>
                        <option value="2" selected>2 persone</option>
                        <option value="3">3 persone</option>
                        <option value="4">4 persone</option>
                        <option value="6">6 persone</option>
                        <option value="8">8 persone</option>
                    </select>
                </div>
                <button type="submit" class="btn-prenota-form">Verifica disponibilità</button>
            </form>

            <p class="prenota-note" id="prenota-note">Ristorante aperto sabato e domenica · Impianti chiusi fino a dicembre</p>
        </div>
    </section>

    <!-- SERVIZI -->
    <section class="section-servizi container">
        <p class="section-label">Cosa offriamo</p>
        <h2 class="section-title">Tutto quello che ti serve</h2>
        <div class="servizi-grid">
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Sci e snowboard</h4>
                <p>8 km di piste, snowpark da 750 m e anello di fondo da 12 km</p>
            </div>
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Trekking ed escursioni</h4>
                <p>Sentieri per tutti i livelli tra Toscana, Liguria ed Emilia</p>
            </div>
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Cucina lunigianese</h4>
                <p>Piatti tradizionali fatti in casa, ingredienti freschi e locali</p>
            </div>
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Noleggio attrezzature</h4>
                <p>Sci, snowboard e tutto il necessario disponibile in loco</p>
            </div>
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Pet friendly</h4>
                <p>I tuoi animali sono i benvenuti in entrambe le strutture</p>
            </div>
            <div class="servizio-item">
                <div class="servizio-icon">&#9651;</div>
                <h4>Wi-Fi gratuito</h4>
                <p>Connessione disponibile in tutte le aree delle strutture</p>
            </div>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>

<script>
    function setSeason(s) {
        const isInv = s === 'inverno';
        document.getElementById('btn-estate').classList.toggle('active', !isInv);
        document.getElementById('btn-inverno').classList.toggle('active', isInv);
        document.getElementById('hero-title').innerHTML = isInv ? 'Neve, piste<br>e silenzio' : 'Tra montagna,<br>cielo e mare';
        document.getElementById('hero-sub').textContent = isInv ? '8 km di piste, snowpark e fondo a 1400 m s.l.m.' : 'Trekking, natura e cucina lunigianese a 1400 m s.l.m.';
        document.getElementById('stagione-label').textContent = isInv ? 'Inverno 2025/26' : 'Estate 2025';
        document.getElementById('prenota-note').textContent = isInv ? 'Impianti aperti · Ristorante self-service · Skipass disponibili' : 'Ristorante aperto sabato e domenica · Impianti chiusi fino a dicembre';
        document.getElementById('hero-bg').className = isInv ? 'hero-bg hero-bg--inverno' : 'hero-bg hero-bg--estate';
    }

    function setTab(t) {
        document.getElementById('tab-camere').classList.toggle('active', t === 'camere');
        document.getElementById('tab-tavoli').classList.toggle('active', t === 'tavoli');
        document.getElementById('form-camere').style.display = t === 'camere' ? 'flex' : 'none';
        document.getElementById('form-tavoli').style.display = t === 'tavoli' ? 'flex' : 'none';
    }
</script>