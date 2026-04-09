<?php
$titolo_pagina = 'Prenota al ristorante';
require_once 'includes/header.php';
require_once 'config/db.php';

$risultato = null;
$cercato = false;
$errore = '';

// Giorni aperti: sabato (6) e domenica (0)
function giorno_aperto(string $data): bool
{
    $giorno = (int)date('w', strtotime($data));
    return $giorno === 0 || $giorno === 6;
}

// Turni disponibili per un giorno
function turni_disponibili(string $data): array
{
    $giorno = (int)date('w', strtotime($data));
    if ($giorno === 6) return ['pranzo', 'cena']; // sabato
    if ($giorno === 0) return ['pranzo'];          // domenica solo pranzo
    return [];
}

$disponibilita = null;
$data_cercata = '';
$turno_cercato = '';
$persone_cercate = 2;


if (isset($_POST['cerca']) || isset($_GET['data'])) {
    $data_cercata    = $_POST['data']    ?? $_GET['data']    ?? '';
    $turno_cercato   = $_POST['turno']   ?? $_GET['turno']   ?? 'pranzo';
    $persone_cercate = (int)($_POST['persone'] ?? $_GET['persone'] ?? 2);
    $cercato = true;

    if (empty($data_cercata)) {
        $errore = 'Seleziona una data.';
    } elseif ($data_cercata < date('Y-m-d')) {
        $errore = 'La data non può essere nel passato.';
    } elseif (!giorno_aperto($data_cercata)) {
        $errore = 'Il ristorante è aperto solo il sabato e la domenica. Scegli un\'altra data.';
    } elseif (!in_array($turno_cercato, turni_disponibili($data_cercata))) {
        $errore = 'La cena è disponibile solo il sabato. La domenica è aperto solo a pranzo.';
    } elseif ($persone_cercate < 1 || $persone_cercate > 40) {
        $errore = 'Numero di persone non valido.';
    } else {
        // Coperti massimi dalle impostazioni
        $stmt = $pdo->prepare("SELECT valore FROM impostazioni WHERE chiave = ?");
        $stmt->execute(['coperti_max_' . $turno_cercato]);
        $coperti_max = (int)($stmt->fetchColumn() ?? 40);

        // Coperti già prenotati per quel giorno e turno
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(persone), 0)
            FROM prenotazioni_ristorante
            WHERE giorno = ?
            AND turno = ?
            AND stato NOT IN ('cancellata')
        ");
        $stmt->execute([$data_cercata, $turno_cercato]);
        $coperti_occupati = (int)$stmt->fetchColumn();

        $coperti_liberi = $coperti_max - $coperti_occupati;

        $disponibilita = [
            'max'      => $coperti_max,
            'occupati' => $coperti_occupati,
            'liberi'   => $coperti_liberi,
            'ok'       => $coperti_liberi >= $persone_cercate,
        ];
    }
}
?>

<main>

    <section class="page-hero page-hero--rifugio">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Rifugio Faggio Crociato</p>
            <h1 class="page-hero-title">Prenota un tavolo</h1>
            <p class="hero-sub">Cucina lunigianese · Aperto sabato e domenica</p>
        </div>
    </section>

    <section class="section-prenota-page">
        <div class="container">

            <!-- TABS -->
            <div class="prenota-page-tabs">
                <a href="/zumzeri/prenota.php" class="prenota-page-tab">Camere</a>
                <a href="/zumzeri/prenota-ristorante.php" class="prenota-page-tab active">Ristorante</a>
            </div>

            <!-- INFO APERTURA -->
            <div class="ristorante-info-box">
                <div class="ristorante-info-item">
                    <strong>Sabato</strong>
                    <span>Pranzo e cena</span>
                </div>
                <div class="ristorante-info-item">
                    <strong>Domenica</strong>
                    <span>Solo pranzo</span>
                </div>
                <div class="ristorante-info-item">
                    <strong>Coperti</strong>
                    <span>Max 40 per turno</span>
                </div>
                <div class="ristorante-info-item">
                    <strong>Prenotazione</strong>
                    <span>Consigliata</span>
                </div>
            </div>

            <!-- FORM RICERCA -->
            <form method="POST" class="form-ricerca">
                <div class="form-ricerca-grid">
                    <div class="form-gruppo">
                        <label for="data">Data</label>
                        <input type="date" id="data" name="data"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($data_cercata) ?>"
                            required>
                    </div>
                    <div class="form-gruppo">
                        <label for="turno">Turno</label>
                        <select id="turno" name="turno">
                            <option value="pranzo" <?= $turno_cercato === 'pranzo' ? 'selected' : '' ?>>Pranzo</option>
                            <option value="cena" <?= $turno_cercato === 'cena' ? 'selected' : '' ?>>Cena</option>
                        </select>
                    </div>
                    <div class="form-gruppo">
                        <label for="persone">Persone</label>
                        <select id="persone" name="persone">
                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>" <?= $persone_cercate == $i ? 'selected' : '' ?>>
                                    <?= $i ?> <?= $i === 1 ? 'persona' : 'persone' ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" name="cerca" class="btn-primary btn-cerca">Verifica</button>
                </div>
            </form>

            <!-- RISULTATO -->
            <?php if ($cercato): ?>
                <div class="risultati-wrap">

                    <?php if ($errore): ?>
                        <div class="alert alert--errore"><?= htmlspecialchars($errore) ?></div>

                    <?php elseif ($disponibilita): ?>

                        <?php if ($disponibilita['ok']): ?>
                            <div class="disponibilita-card disponibilita-card--ok">
                                <div class="disponibilita-info">
                                    <h3>Disponibilità confermata</h3>
                                    <p>
                                        <?= date('l d/m/Y', strtotime($data_cercata)) ?> —
                                        <?= ucfirst($turno_cercato) ?> —
                                        <?= $persone_cercate ?> <?= $persone_cercate === 1 ? 'persona' : 'persone' ?>
                                    </p>
                                    <p class="coperti-rimasti">Posti ancora disponibili: <strong><?= $disponibilita['liberi'] ?></strong></p>
                                </div>
                                <a href="/zumzeri/prenota-ristorante-conferma.php?data=<?= urlencode($data_cercata) ?>&turno=<?= urlencode($turno_cercato) ?>&persone=<?= $persone_cercate ?>"
                                    class="btn-primary">
                                    Procedi con la prenotazione
                                </a>
                            </div>

                        <?php else: ?>
                            <div class="alert alert--vuoto">
                                <strong>Nessun posto disponibile</strong> per <?= $persone_cercate ?> <?= $persone_cercate === 1 ? 'persona' : 'persone' ?>
                                il <?= date('d/m/Y', strtotime($data_cercata)) ?> a <?= $turno_cercato ?>.<br>
                                Prova un altro giorno o un altro turno, oppure <a href="/zumzeri/contatti.php">contattaci</a> direttamente.
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>