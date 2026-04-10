<?php
$titolo_pagina = 'Privacy Policy';
$meta_descrizione = 'Informativa sulla privacy e sul trattamento dei dati personali di Zum Zeri, ai sensi del Reg. UE 2016/679 (GDPR).';
require_once 'includes/header.php';
?>

<main>

    <section class="page-hero page-hero--contatti">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Zum Zeri</p>
            <h1 class="page-hero-title">Privacy Policy</h1>
            <p class="hero-sub">Informativa ai sensi del Reg. UE 2016/679 (GDPR)</p>
        </div>
    </section>

    <section class="section-privacy container">

        <div class="privacy-box">

            <h2>Titolare del trattamento</h2>
            <p>Il titolare del trattamento dei dati personali è <strong>Zum Zeri</strong>, con sede a Passo dei Due Santi, 54029 Zeri (MS). Per qualsiasi questione relativa alla privacy puoi contattarci all'indirizzo email indicato nella pagina contatti.</p>

            <h2>Dati raccolti e finalità</h2>
            <p>Il sito raccoglie dati personali esclusivamente nei seguenti casi:</p>
            <ul>
                <li><strong>Prenotazione camere:</strong> nome, cognome, email, telefono, numero di ospiti, date di soggiorno. I dati vengono utilizzati per gestire la prenotazione e contattare il cliente.</li>
                <li><strong>Prenotazione ristorante:</strong> nome, cognome, email, telefono, numero di persone, data e turno. I dati vengono utilizzati per gestire la prenotazione e contattare il cliente.</li>
                <li><strong>Modulo di contatto:</strong> nome, cognome, email, messaggio. I dati vengono utilizzati per rispondere alla richiesta.</li>
            </ul>

            <h2>Base giuridica</h2>
            <p>Il trattamento dei dati avviene sulla base del consenso espresso dall'utente (Art. 6, par. 1, lett. a del GDPR) e per l'esecuzione di un contratto o misure precontrattuali (Art. 6, par. 1, lett. b del GDPR).</p>

            <h2>Conservazione dei dati</h2>
            <p>I dati relativi alle prenotazioni vengono conservati per il tempo necessario alla gestione del rapporto contrattuale e agli obblighi di legge. I dati del modulo di contatto vengono eliminati una volta evasa la richiesta.</p>

            <h2>Diritti dell'interessato</h2>
            <p>Ai sensi del GDPR, hai il diritto di accedere ai tuoi dati personali, richiederne la rettifica o la cancellazione, opporti al trattamento e richiedere la portabilità dei dati. Per esercitare questi diritti puoi contattarci tramite la pagina contatti.</p>

            <h2>Cookie</h2>
            <p>Questo sito utilizza esclusivamente cookie tecnici necessari al funzionamento delle sessioni di navigazione. Non vengono utilizzati cookie di profilazione o di terze parti a fini pubblicitari.</p>

            <h2>Sicurezza</h2>
            <p>I dati personali sono conservati in database protetti accessibili solo al personale autorizzato. Le password amministrative sono conservate in forma hashata e non sono mai accessibili in chiaro.</p>

            <h2>Modifiche</h2>
            <p>Questa informativa può essere aggiornata periodicamente. L'ultima modifica è del <strong><?= date('d/m/Y') ?></strong>.</p>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>