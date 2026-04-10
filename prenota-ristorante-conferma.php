<?php
$titolo_pagina = 'Conferma prenotazione ristorante';
require_once 'includes/header.php';
require_once 'config/db.php';

// Controlla modalità self-service e ristorante attivo
$stmt = $pdo->prepare("SELECT valore FROM impostazioni WHERE chiave = 'modalita_selfservice'");
$stmt->execute();
$selfservice = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT valore FROM impostazioni WHERE chiave = 'ristorante_attivo'");
$stmt->execute();
$ristorante_attivo = $stmt->fetchColumn();

$data    = $_GET['data'] ?? '';
$turno   = $_GET['turno'] ?? '';
$persone = (int)($_GET['persone'] ?? 0);

if (!$data || !$turno || !$persone) {
    header('Location: /zumzeri/prenota-ristorante.php');
    exit;
}

// Blocca se ristorante disattivo
if ($ristorante_attivo === '0') {
    header('Location: /zumzeri/prenota-ristorante.php');
    exit;
}

// Blocca se self-service e turno è pranzo
if ($selfservice === '1' && $turno === 'pranzo') {
    header('Location: /zumzeri/prenota-ristorante.php');
    exit;
}

$giorni_ita = ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'];
$giorno_num = (int)date('w', strtotime($data));
$data_leggibile = $giorni_ita[$giorno_num] . ' ' . date('d/m/Y', strtotime($data));

$errore  = '';
$successo = false;
$codice  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $cognome  = trim($_POST['cognome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $note     = trim($_POST['note'] ?? '');

    if (!$nome || !$cognome || !$email) {
        $errore = 'Compila tutti i campi obbligatori.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = 'Inserisci un indirizzo email valido.';
    } else {
        $stmt = $pdo->prepare("SELECT valore FROM impostazioni WHERE chiave = ?");
        $stmt->execute(['coperti_max_' . $turno]);
        $coperti_max = (int)($stmt->fetchColumn() ?? 40);

        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(persone), 0)
            FROM prenotazioni_ristorante
            WHERE giorno = ? AND turno = ? AND stato NOT IN ('cancellata')
        ");
        $stmt->execute([$data, $turno]);
        $coperti_occupati = (int)$stmt->fetchColumn();

        if (($coperti_max - $coperti_occupati) < $persone) {
            $errore = 'Spiacenti, i posti non sono più disponibili. Torna indietro e scegli un\'altra data.';
        } else {
            $codice = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

            $stmt = $pdo->prepare("
                INSERT INTO prenotazioni_ristorante
                (nome, cognome, email, telefono, persone, giorno, turno, note, codice)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $nome,
                $cognome,
                $email,
                $telefono,
                $persone,
                $data,
                $turno,
                $note,
                $codice
            ]);

            require_once 'includes/mailer.php';
            invia_email_conferma_ristorante([
                'nome'           => $nome,
                'cognome'        => $cognome,
                'email'          => $email,
                'codice'         => $codice,
                'data'           => $data,
                'data_leggibile' => $data_leggibile,
                'turno'          => $turno,
                'persone'        => $persone,
            ]);

            $successo = true;
        }
    }
}
?>

<main>

    <section class="page-hero page-hero--rifugio">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Rifugio Faggio Crociato</p>
            <h1 class="page-hero-title">Conferma prenotazione</h1>
            <p class="hero-sub"><?= $data_leggibile ?> — <?= ucfirst($turno) ?> — <?= $persone ?> <?= $persone === 1 ? 'persona' : 'persone' ?></p>
        </div>
    </section>

    <section class="section-prenota-page">
        <div class="container">

            <?php if ($successo): ?>

                <div class="conferma-box">
                    <div class="conferma-icona">✓</div>
                    <h2 class="conferma-titolo">Prenotazione ricevuta!</h2>
                    <p class="conferma-sub">Abbiamo ricevuto la tua richiesta. Ti contatteremo a breve per la conferma definitiva.</p>
                    <div class="conferma-codice">
                        <span>Il tuo codice prenotazione</span>
                        <strong><?= $codice ?></strong>
                        <small>Conserva questo codice per verificare lo stato della tua prenotazione</small>
                    </div>
                    <div class="conferma-riepilogo">
                        <div class="riepilogo-row"><span>Data</span><strong><?= $data_leggibile ?></strong></div>
                        <div class="riepilogo-row"><span>Turno</span><strong><?= ucfirst($turno) ?></strong></div>
                        <div class="riepilogo-row"><span>Persone</span><strong><?= $persone ?></strong></div>
                    </div>
                    <a href="/zumzeri/index.php" class="btn-primary" style="display:inline-block; margin-top:24px;">Torna alla home</a>
                </div>

            <?php else: ?>

                <div class="riepilogo-camera">
                    <h2>Riepilogo</h2>
                    <div class="riepilogo-row"><span>Data</span><strong><?= $data_leggibile ?></strong></div>
                    <div class="riepilogo-row"><span>Turno</span><strong><?= ucfirst($turno) ?></strong></div>
                    <div class="riepilogo-row"><span>Persone</span><strong><?= $persone ?></strong></div>
                    <a href="/zumzeri/prenota-ristorante.php" class="link-back">← Cambia data</a>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert--errore"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>

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
                        <label for="note">Allergie o richieste particolari</label>
                        <textarea id="note" name="note" rows="3"
                            placeholder="Allergie, intolleranze, occasioni speciali..."><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
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