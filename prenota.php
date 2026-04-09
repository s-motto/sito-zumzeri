<?php
$titolo_pagina = 'Prenota';
require_once 'includes/header.php';
require_once 'config/db.php';

$risultati = [];
$cercato = false;
$errore = '';

if (isset($_POST['cerca']) || (isset($_GET['check_in']) && isset($_GET['check_out']))) {
    $check_in  = $_POST['check_in']  ?? $_GET['check_in']  ?? '';
    $check_out = $_POST['check_out'] ?? $_GET['check_out'] ?? '';
    $ospiti    = (int)($_POST['ospiti'] ?? $_GET['ospiti'] ?? 1);
    $cercato   = true;


    if (empty($check_in) || empty($check_out)) {
        $errore = 'Seleziona le date di arrivo e partenza.';
    } elseif ($check_in >= $check_out) {
        $errore = 'La data di partenza deve essere successiva alla data di arrivo.';
    } elseif ($check_in < date('Y-m-d')) {
        $errore = 'La data di arrivo non può essere nel passato.';
    } else {
        // Camere disponibili = non prenotate in quel periodo, con abbastanza posti
        $sql = "
            SELECT c.*
            FROM camere c
            WHERE c.stato = 'disponibile'
            AND c.posti >= :ospiti
            AND c.id NOT IN (
                SELECT camera_id
                FROM prenotazioni_camere
                WHERE stato NOT IN ('cancellata')
                AND check_in < :check_out
                AND check_out > :check_in
            )
            ORDER BY c.posti ASC, c.numero ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ospiti'    => $ospiti,
            ':check_in'  => $check_in,
            ':check_out' => $check_out,
        ]);
        $risultati = $stmt->fetchAll();
    }
}
?>

<main>

    <section class="page-hero page-hero--contatti">
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content container">
            <p class="hero-eyebrow">Gran Baita Lunigiana</p>
            <h1 class="page-hero-title">Prenota una camera</h1>
            <p class="hero-sub">Verifica la disponibilità e prenota direttamente online</p>
        </div>
    </section>

    <section class="section-prenota-page">
        <div class="container">

            <!-- TABS -->
            <div class="prenota-page-tabs">
                <a href="/zumzeri/prenota.php" class="prenota-page-tab active">Camere</a>
                <a href="/zumzeri/prenota-ristorante.php" class="prenota-page-tab">Ristorante</a>
            </div>

            <!-- FORM RICERCA -->
            <form method="POST" class="form-ricerca">
                <div class="form-ricerca-grid">
                    <div class="form-gruppo">
                        <label for="check_in">Arrivo</label>
                        <input type="date" id="check_in" name="check_in"
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($_POST['check_in'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-gruppo">
                        <label for="check_out">Partenza</label>
                        <input type="date" id="check_out" name="check_out"
                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                            value="<?= htmlspecialchars($_POST['check_out'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-gruppo">
                        <label for="ospiti">Ospiti</label>
                        <select id="ospiti" name="ospiti">
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?= $i ?>" <?= (($_POST['ospiti'] ?? 2) == $i) ? 'selected' : '' ?>>
                                    <?= $i ?> <?= $i === 1 ? 'ospite' : 'ospiti' ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" name="cerca" class="btn-primary btn-cerca">Cerca</button>
                </div>
            </form>

            <!-- RISULTATI -->
            <?php if ($cercato): ?>
                <div class="risultati-wrap">

                    <?php if ($errore): ?>
                        <div class="alert alert--errore"><?= htmlspecialchars($errore) ?></div>

                    <?php elseif (empty($risultati)): ?>
                        <div class="alert alert--vuoto">
                            <strong>Nessuna camera disponibile</strong> per le date selezionate con <?= $ospiti ?> ospiti.<br>
                            Prova a cambiare le date o il numero di ospiti, oppure <a href="/zumzeri/contatti.php">contattaci</a> per verificare disponibilità personalizzate.
                        </div>

                    <?php else: ?>
                        <p class="risultati-label">
                            <?= count($risultati) ?> <?= count($risultati) === 1 ? 'camera disponibile' : 'camere disponibili' ?>
                            dal <strong><?= date('d/m/Y', strtotime($check_in)) ?></strong>
                            al <strong><?= date('d/m/Y', strtotime($check_out)) ?></strong>
                        </p>

                        <div class="risultati-grid">
                            <?php foreach ($risultati as $camera): ?>
                                <div class="risultato-card">
                                    <div class="risultato-img risultato-img--<?= $camera['posti'] ?>"></div>
                                    <div class="risultato-body">
                                        <div class="risultato-header">
                                            <h3>Camera <?= htmlspecialchars($camera['numero']) ?></h3>
                                            <span class="risultato-posti"><?= $camera['posti'] ?> posti</span>
                                        </div>
                                        <p class="risultato-piano">
                                            Piano <?= $camera['piano'] ?>
                                            <?php if ($camera['posti'] > $ospiti): ?>
                                                · <span style="color: var(--colore-legno); font-size:12px;">
                                                    Camera da <?= $camera['posti'] ?> — <?= $camera['posti'] - $ospiti ?> posto/i extra
                                                </span>
                                            <?php endif; ?>
                                        </p>

                                        <ul class="camera-dotazioni">
                                            <li>Bagno privato</li>
                                            <li>Riscaldamento</li>
                                            <li>Wi-Fi</li>
                                        </ul>
                                        <a href="/zumzeri/prenota-camera.php?camera=<?= $camera['id'] ?>&check_in=<?= urlencode($check_in) ?>&check_out=<?= urlencode($check_out) ?>&ospiti=<?= $ospiti ?>"
                                            class="btn-primary btn-prenota-camera">
                                            Prenota questa camera
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>