<?php
$titolo_pagina = 'Conferma prenotazione';
require_once 'includes/header.php';
require_once 'config/db.php';

// Parametri dalla pagina precedente
$camera_id = (int)($_GET['camera'] ?? 0);
$check_in  = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$ospiti    = (int)($_GET['ospiti'] ?? 1);

// Validazione parametri
if (!$camera_id || !$check_in || !$check_out) {
    header('Location: /zumzeri/prenota.php');
    exit;
}

// Carica dati camera
$stmt = $pdo->prepare("SELECT * FROM camere WHERE id = ? AND stato = 'disponibile'");
$stmt->execute([$camera_id]);
$camera = $stmt->fetch();

if (!$camera) {
    header('Location: /zumzeri/prenota.php');
    exit;
}

// Calcola notti
$notti = (new DateTime($check_in))->diff(new DateTime($check_out))->days;

$errore  = '';
$successo = false;
$codice  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $cognome  = trim($_POST['cognome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $note     = trim($_POST['note'] ?? '');

    // Validazione
    if (!$nome || !$cognome || !$email) {
        $errore = 'Compila tutti i campi obbligatori.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = 'Inserisci un indirizzo email valido.';
    } else {
        // Verifica che la camera sia ancora disponibile
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM prenotazioni_camere
            WHERE camera_id = ?
            AND stato NOT IN ('cancellata')
            AND check_in < ?
            AND check_out > ?
        ");
        $stmt->execute([$camera_id, $check_out, $check_in]);
        $occupata = $stmt->fetchColumn();

        if ($occupata) {
            $errore = 'Spiacenti, questa camera è stata appena prenotata da qualcun altro. Torna indietro e scegli un\'altra camera.';
        } else {
            // Genera codice univoco
            $codice = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            // Inserisci prenotazione
            $stmt = $pdo->prepare("
                INSERT INTO prenotazioni_camere
                (camera_id, nome, cognome, email, telefono, ospiti, check_in, check_out, note, codice)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $camera_id,
                $nome,
                $cognome,
                $email,
                $telefono,
                $ospiti,
                $check_in,
                $check_out,
                $note,
                $codice
            ]);

            require_once 'includes/mailer.php';
            invia_email_conferma_camera([
                'nome'      => $nome,
                'cognome'   => $cognome,
                'email'     => $email,
                'codice'    => $codice,
                'numero'    => $camera['numero'],
                'piano'     => $camera['piano'],
                'check_in'  => $check_in,
                'check_out' => $check_out,
                'ospiti'    => $ospiti,
            ]);

            $successo = true;
        }
    }
}
?>

<main>

    <section class="page-hero page-hero--contatti">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Gran Baita Lunigiana</p>
            <h1 class="page-hero-title">Conferma la prenotazione</h1>
            <p class="hero-sub">Camera <?= htmlspecialchars($camera['numero']) ?> · <?= $notti ?> nott<?= $notti === 1 ? 'e' : 'i' ?></p>
        </div>
    </section>

    <section class="section-prenota-page">
        <div class="container">

            <?php if ($successo): ?>

                <!-- CONFERMA -->
                <div class="conferma-box">
                    <div class="conferma-icona">✓</div>
                    <h2 class="conferma-titolo">Prenotazione ricevuta!</h2>
                    <p class="conferma-sub">Abbiamo ricevuto la tua richiesta. Ti contatteremo presto per la conferma definitiva.</p>
                    <div class="conferma-codice">
                        <span>Il tuo codice prenotazione</span>
                        <strong><?= $codice ?></strong>
                        <small>Conserva questo codice per verificare lo stato della tua prenotazione</small>
                    </div>
                    <div class="conferma-riepilogo">
                        <div class="riepilogo-row"><span>Camera</span><strong><?= htmlspecialchars($camera['numero']) ?> — Piano <?= $camera['piano'] ?></strong></div>
                        <div class="riepilogo-row"><span>Arrivo</span><strong><?= date('d/m/Y', strtotime($check_in)) ?></strong></div>
                        <div class="riepilogo-row"><span>Partenza</span><strong><?= date('d/m/Y', strtotime($check_out)) ?></strong></div>
                        <div class="riepilogo-row"><span>Notti</span><strong><?= $notti ?></strong></div>
                        <div class="riepilogo-row"><span>Ospiti</span><strong><?= $ospiti ?></strong></div>
                    </div>
                    <a href="/zumzeri/index.php" class="btn-primary" style="display:inline-block; margin-top:24px;">Torna alla home</a>
                </div>

            <?php else: ?>

                <!-- RIEPILOGO CAMERA -->
                <div class="riepilogo-camera">
                    <div class="riepilogo-camera-info">
                        <h2>Riepilogo</h2>
                        <div class="riepilogo-row"><span>Camera</span><strong><?= htmlspecialchars($camera['numero']) ?> — Piano <?= $camera['piano'] ?></strong></div>
                        <div class="riepilogo-row"><span>Posti</span><strong><?= $camera['posti'] ?></strong></div>
                        <div class="riepilogo-row"><span>Arrivo</span><strong><?= date('d/m/Y', strtotime($check_in)) ?></strong></div>
                        <div class="riepilogo-row"><span>Partenza</span><strong><?= date('d/m/Y', strtotime($check_out)) ?></strong></div>
                        <div class="riepilogo-row"><span>Notti</span><strong><?= $notti ?></strong></div>
                        <div class="riepilogo-row"><span>Ospiti</span><strong><?= $ospiti ?></strong></div>
                    </div>
                    <a href="/zumzeri/prenota.php" class="link-back">← Cambia camera</a>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert--errore"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>

                <!-- FORM DATI CLIENTE -->
                <form method="POST" class="form-dati-cliente">
                    <h2 class="form-sezione-titolo">I tuoi dati</h2>
                    <div class="form-row">
                        <div class="form-gruppo">
                            <label for="nome">Nome <span class="obbligatorio">*</span></label>
                            <input type="text" id="nome" name="nome"
                                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                                placeholder="Mario" required>
                        </div>
                        <div class="form-gruppo">
                            <label for="cognome">Cognome <span class="obbligatorio">*</span></label>
                            <input type="text" id="cognome" name="cognome"
                                value="<?= htmlspecialchars($_POST['cognome'] ?? '') ?>"
                                placeholder="Rossi" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-gruppo">
                            <label for="email">Email <span class="obbligatorio">*</span></label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                placeholder="mario@email.it" required>
                        </div>
                        <div class="form-gruppo">
                            <label for="telefono">Telefono</label>
                            <input type="tel" id="telefono" name="telefono"
                                value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                                placeholder="+39 XXX XXX XXXX">
                        </div>
                    </div>
                    <div class="form-gruppo">
                        <label for="note">Note o richieste particolari</label>
                        <textarea id="note" name="note" rows="3"
                            placeholder="Allergie, esigenze particolari, orario di arrivo previsto..."><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                    </div>
                    <div class="form-privacy">
                        <input type="checkbox" id="privacy" name="privacy" required>
                        <label for="privacy">Acconsento al trattamento dei dati personali ai sensi del Reg. UE 2016/679 (GDPR)</label>
                    </div>
                    <button type="submit" class="btn-primary">Conferma prenotazione</button>
                    <p class="form-nota">* Campi obbligatori. La prenotazione sarà confermata dopo verifica da parte dello staff.</p>
                </form>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>