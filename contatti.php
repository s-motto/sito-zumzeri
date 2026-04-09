<?php
$titolo_pagina = 'Contatti';
require_once 'includes/header.php';
?>

<main>

    <!-- HERO -->
    <section class="page-hero page-hero--contatti">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Zum Zeri · Passo dei Due Santi</p>
            <h1 class="page-hero-title">Contatti e dove siamo</h1>
            <p class="hero-sub">Siamo a Zeri, in provincia di Massa-Carrara, al confine tra tre regioni</p>
        </div>
    </section>

    <!-- CONTATTI + MAPPA -->
    <section class="section-contatti container">

        <div class="contatti-grid">

            <!-- INFO -->
            <div class="contatti-info">
                <p class="section-label">Scrivici o chiamaci</p>
                <h2 class="section-title">Come raggiungerci</h2>

                <div class="contatto-blocco">
                    <h3>Indirizzo</h3>
                    <p>Passo dei Due Santi<br>54029 Zeri (MS)<br>Lunigiana, Toscana</p>
                </div>

                <div class="contatto-blocco">
                    <h3>Telefono</h3>
                    <a href="tel:+39XXXXXXXXXX">+39 XXX XXX XXXX</a>
                    <span class="contatto-note">Disponibile tutti i giorni durante l'orario di apertura</span>
                </div>

                <div class="contatto-blocco">
                    <h3>Email</h3>
                    <a href="mailto:info@zumzeri.it">info@zumzeri.it</a>
                    <span class="contatto-note">Risposta entro 24 ore</span>
                </div>

                <div class="contatto-blocco">
                    <h3>Seguici</h3>
                    <div class="social-links">
                        <a href="https://www.facebook.com/zumzerieu/" target="_blank">Facebook</a>
                        <a href="https://www.instagram.com/zum_zeri/" target="_blank">Instagram</a>
                    </div>
                </div>

                <div class="contatto-blocco">
                    <h3>Come arrivare</h3>
                    <p>Da La Spezia: SP566 direzione Pontremoli, poi indicazioni per Zeri.<br>
                        Da Parma: A15 uscita Pontremoli, poi indicazioni per Zeri.<br>
                        Da Massa: SP72 direzione Villafranca, poi Zeri.</p>
                </div>
            </div>

            <!-- MAPPA -->
            <div class="contatti-mappa">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2851.16138272217!2d9.749778074996192!3d44.38880880454632!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12d4db1a68c7d62b%3A0xec03e7c57d5f47df!2sZum%20Zeri!5e0!3m2!1sit!2sit!4v1775758328849!5m2!1sit!2sit"
                    width="100%"
                    height="100%"
                    style="border:0; border-radius:4px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mappa Zum Zeri — Passo dei Due Santi">
                </iframe>
            </div>

        </div>

    </section>

    <!-- FORM CONTATTO -->
    <section class="section-form-contatto">
        <div class="container">
            <p class="section-label">Scrivici</p>
            <h2 class="section-title">Hai una domanda?</h2>
            <p class="section-sub">Per informazioni su prenotazioni, disponibilità o qualsiasi altra cosa — siamo qui.</p>

            <form class="form-contatto" action="invia-contatto.php" method="POST">
                <div class="form-row">
                    <div class="form-gruppo">
                        <label for="nome">Nome e cognome</label>
                        <input type="text" id="nome" name="nome" placeholder="Mario Rossi" required>
                    </div>
                    <div class="form-gruppo">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="mario@email.it" required>
                    </div>
                </div>
                <div class="form-gruppo">
                    <label for="oggetto">Oggetto</label>
                    <select id="oggetto" name="oggetto">
                        <option>Informazioni camere</option>
                        <option>Prenotazione ristorante</option>
                        <option>Informazioni impianti</option>
                        <option>Altro</option>
                    </select>
                </div>
                <div class="form-gruppo">
                    <label for="messaggio">Messaggio</label>
                    <textarea id="messaggio" name="messaggio" rows="5" placeholder="Scrivi il tuo messaggio..." required></textarea>
                </div>
                <button type="submit" class="btn-primary">Invia messaggio</button>
            </form>

        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>