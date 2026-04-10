<?php
require_once '../admin/auth/session.php';
richiedi_login();
require_once '../config/db.php';

$messaggio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifica_csrf();
    $impostazioni = [
        'stagione'             => $_POST['stagione'] ?? 'estate',
        'ristorante_attivo'    => isset($_POST['ristorante_attivo']) ? '1' : '0',
        'coperti_max_pranzo'   => (int)($_POST['coperti_max_pranzo'] ?? 40),
        'coperti_max_cena'     => (int)($_POST['coperti_max_cena'] ?? 40),
        'modalita_selfservice' => isset($_POST['modalita_selfservice']) ? '1' : '0',
    ];

    $stmt = $pdo->prepare("
        INSERT INTO impostazioni (chiave, valore)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE valore = VALUES(valore)
    ");

    foreach ($impostazioni as $chiave => $valore) {
        $stmt->execute([$chiave, $valore]);
    }

    $messaggio = 'Impostazioni salvate correttamente.';
}

// Carica impostazioni correnti
$stmt = $pdo->query("SELECT chiave, valore FROM impostazioni");
$rows = $stmt->fetchAll();
$imp = [];
foreach ($rows as $row) {
    $imp[$row['chiave']] = $row['valore'];
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php">Prenotazioni camere</a>
            <a href="/zumzeri/admin/calendario.php">Calendario camere</a>
            <a href="/zumzeri/admin/bookings/ristorante.php">Prenotazioni ristorante</a>
            <a href="/zumzeri/admin/camere.php">Gestione camere</a>
            <a href="/zumzeri/admin/impostazioni.php" class="active">Impostazioni</a>
        </nav>
        <div class="admin-footer">
            <span><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>
            <a href="/zumzeri/admin/logout.php">Esci</a>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>Impostazioni</h1>
        </div>

        <?php if ($messaggio): ?>
            <div class="alert alert--ok" style="margin-bottom: 24px;"><?= htmlspecialchars($messaggio) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= genera_csrf() ?>">

            <!-- STAGIONE -->
            <div class="admin-section" style="margin-bottom: 20px;">
                <div class="admin-section-header">
                    <h2>Stagione</h2>
                </div>
                <div class="imp-body">
                    <div class="imp-row">
                        <div class="imp-label">
                            <strong>Stagione attiva</strong>
                            <span>Cambia il contenuto stagionale del sito (estate/inverno)</span>
                        </div>
                        <div class="imp-control">
                            <select name="stagione" class="imp-select">
                                <option value="estate" <?= ($imp['stagione'] ?? 'estate') === 'estate'  ? 'selected' : '' ?>>Estate</option>
                                <option value="inverno" <?= ($imp['stagione'] ?? 'estate') === 'inverno' ? 'selected' : '' ?>>Inverno</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RISTORANTE -->
            <div class="admin-section" style="margin-bottom: 20px;">
                <div class="admin-section-header">
                    <h2>Ristorante</h2>
                </div>
                <div class="imp-body">
                    <div class="imp-row">
                        <div class="imp-label">
                            <strong>Prenotazioni online attive</strong>
                            <span>Se disattivato, il form di prenotazione ristorante non accetta nuove prenotazioni</span>
                        </div>
                        <div class="imp-control">
                            <label class="toggle">
                                <input type="checkbox" name="ristorante_attivo"
                                    <?= ($imp['ristorante_attivo'] ?? '1') === '1' ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="imp-row">
                        <div class="imp-label">
                            <strong>Modalità self-service</strong>
                            <span>Attiva in inverno — disabilita le prenotazioni online solo per il pranzo, la cena rimane prenotabile</span>
                        </div>
                        <div class="imp-control">
                            <label class="toggle">
                                <input type="checkbox" name="modalita_selfservice"
                                    <?= ($imp['modalita_selfservice'] ?? '0') === '1' ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="imp-row">
                        <div class="imp-label">
                            <strong>Coperti massimi pranzo</strong>
                            <span>Numero massimo di coperti accettabili per il turno di pranzo</span>
                        </div>
                        <div class="imp-control">
                            <input type="number" name="coperti_max_pranzo" min="1" max="200"
                                value="<?= (int)($imp['coperti_max_pranzo'] ?? 40) ?>"
                                class="imp-input-num">
                        </div>
                    </div>
                    <div class="imp-row">
                        <div class="imp-label">
                            <strong>Coperti massimi cena</strong>
                            <span>Numero massimo di coperti accettabili per il turno di cena</span>
                        </div>
                        <div class="imp-control">
                            <input type="number" name="coperti_max_cena" min="1" max="200"
                                value="<?= (int)($imp['coperti_max_cena'] ?? 40) ?>"
                                class="imp-input-num">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="display:inline-block;">Salva impostazioni</button>

        </form>
    </main>

</body>

</html>