<?php
require_once '../admin/auth/session.php';
richiedi_login();
require_once '../config/db.php';

// Statistiche rapide
$stmt = $pdo->query("SELECT COUNT(*) FROM prenotazioni_camere WHERE stato = 'in_attesa'");
$camere_in_attesa = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM prenotazioni_ristorante WHERE stato = 'in_attesa'");
$ristorante_in_attesa = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM prenotazioni_camere WHERE check_in = CURDATE()");
$arrivi_oggi = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM prenotazioni_camere WHERE check_out = CURDATE()");
$partenze_oggi = $stmt->fetchColumn();

// Ultime prenotazioni camere
$stmt = $pdo->query("
    SELECT pc.*, c.numero, c.piano
    FROM prenotazioni_camere pc
    JOIN camere c ON pc.camera_id = c.id
    ORDER BY pc.creata_il DESC
    LIMIT 5
");
$ultime_camere = $stmt->fetchAll();

// Ultime prenotazioni ristorante
$stmt = $pdo->query("
    SELECT * FROM prenotazioni_ristorante
    ORDER BY creata_il DESC
    LIMIT 5
");
$ultime_ristorante = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php" class="active">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php">Prenotazioni camere</a>
            <a href="/zumzeri/admin/bookings/ristorante.php">Prenotazioni ristorante</a>
            <a href="/zumzeri/admin/impostazioni.php">Impostazioni</a>
        </nav>
        <div class="admin-footer">
            <span><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>
            <a href="/zumzeri/admin/logout.php">Esci</a>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <span class="admin-date"><?= date('d/m/Y') ?></span>
        </div>

        <!-- STATISTICHE -->
        <div class="admin-stats">
            <div class="stat-card">
                <span class="stat-label">Camere in attesa</span>
                <strong class="stat-num <?= $camere_in_attesa > 0 ? 'stat-num--alert' : '' ?>"><?= $camere_in_attesa ?></strong>
            </div>
            <div class="stat-card">
                <span class="stat-label">Ristorante in attesa</span>
                <strong class="stat-num <?= $ristorante_in_attesa > 0 ? 'stat-num--alert' : '' ?>"><?= $ristorante_in_attesa ?></strong>
            </div>
            <div class="stat-card">
                <span class="stat-label">Arrivi oggi</span>
                <strong class="stat-num"><?= $arrivi_oggi ?></strong>
            </div>
            <div class="stat-card">
                <span class="stat-label">Partenze oggi</span>
                <strong class="stat-num"><?= $partenze_oggi ?></strong>
            </div>
        </div>

        <!-- ULTIME PRENOTAZIONI CAMERE -->
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Ultime prenotazioni camere</h2>
                <a href="/zumzeri/admin/bookings/camere.php">Vedi tutte →</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Cliente</th>
                        <th>Camera</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Ospiti</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ultime_camere)): ?>
                        <tr>
                            <td colspan="7" class="admin-empty">Nessuna prenotazione</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ultime_camere as $p): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['codice']) ?></code></td>
                                <td><?= htmlspecialchars($p['nome'] . ' ' . $p['cognome']) ?></td>
                                <td>Camera <?= htmlspecialchars($p['numero']) ?> — Piano <?= $p['piano'] ?></td>
                                <td><?= date('d/m/Y', strtotime($p['check_in'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['check_out'])) ?></td>
                                <td><?= $p['ospiti'] ?></td>
                                <td><span class="stato-badge stato-<?= $p['stato'] ?>"><?= ucfirst(str_replace('_', ' ', $p['stato'])) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ULTIME PRENOTAZIONI RISTORANTE -->
        <div class="admin-section">
            <div class="admin-section-header">
                <h2>Ultime prenotazioni ristorante</h2>
                <a href="/zumzeri/admin/bookings/ristorante.php">Vedi tutte →</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Turno</th>
                        <th>Persone</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ultime_ristorante)): ?>
                        <tr>
                            <td colspan="6" class="admin-empty">Nessuna prenotazione</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ultime_ristorante as $p): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['codice']) ?></code></td>
                                <td><?= htmlspecialchars($p['nome'] . ' ' . $p['cognome']) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['giorno'])) ?></td>
                                <td><?= ucfirst($p['turno']) ?></td>
                                <td><?= $p['persone'] ?></td>
                                <td><span class="stato-badge stato-<?= $p['stato'] ?>"><?= ucfirst(str_replace('_', ' ', $p['stato'])) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>

</html>