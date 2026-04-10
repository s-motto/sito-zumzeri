<?php
require_once '../../admin/auth/session.php';
richiedi_login();
require_once '../../config/db.php';

// Cambio stato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambia_stato'])) {
    verifica_csrf();
    $id    = (int)$_POST['id'];
    $stato = $_POST['stato'];
    $stati_validi = ['in_attesa', 'confermata', 'cancellata', 'completata'];
    if (in_array($stato, $stati_validi)) {
        $stmt = $pdo->prepare("UPDATE prenotazioni_camere SET stato = ? WHERE id = ?");
        $stmt->execute([$stato, $id]);
    }
    header('Location: /zumzeri/admin/bookings/camere.php');
    exit;
}

// Filtri
$filtro_stato = $_GET['stato'] ?? '';
$filtro_cerca = trim($_GET['cerca'] ?? '');

$where = ['1=1'];
$params = [];

if ($filtro_stato) {
    $where[] = 'pc.stato = ?';
    $params[] = $filtro_stato;
}

if ($filtro_cerca) {
    $where[] = '(pc.codice LIKE ? OR pc.nome LIKE ? OR pc.cognome LIKE ? OR pc.email LIKE ?)';
    $cerca = '%' . $filtro_cerca . '%';
    $params = array_merge($params, [$cerca, $cerca, $cerca, $cerca]);
}

$sql = "
    SELECT pc.*, c.numero, c.piano
    FROM prenotazioni_camere pc
    JOIN camere c ON pc.camera_id = c.id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY pc.creata_il DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prenotazioni = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazioni camere — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php" class="active">Prenotazioni camere</a>
            <a href="/zumzeri/admin/bookings/ristorante.php">Prenotazioni ristorante</a>
            <a href="/zumzeri/admin/camere.php">Gestione camere</a>
            <a href="/zumzeri/admin/impostazioni.php">Impostazioni</a>
        </nav>
        <div class="admin-footer">
            <span><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>
            <a href="/zumzeri/admin/logout.php">Esci</a>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>Prenotazioni camere</h1>
            <span class="admin-date"><?= count($prenotazioni) ?> prenotazioni</span>
        </div>

        <!-- FILTRI -->
        <form method="GET" class="admin-filtri">
            <input type="text" name="cerca" placeholder="Cerca per nome, email o codice..."
                value="<?= htmlspecialchars($filtro_cerca) ?>">
            <select name="stato">
                <option value="">Tutti gli stati</option>
                <option value="in_attesa" <?= $filtro_stato === 'in_attesa'  ? 'selected' : '' ?>>In attesa</option>
                <option value="confermata" <?= $filtro_stato === 'confermata' ? 'selected' : '' ?>>Confermata</option>
                <option value="cancellata" <?= $filtro_stato === 'cancellata' ? 'selected' : '' ?>>Cancellata</option>
                <option value="completata" <?= $filtro_stato === 'completata' ? 'selected' : '' ?>>Completata</option>
            </select>
            <button type="submit" class="btn-admin-filtro">Filtra</button>
            <?php if ($filtro_stato || $filtro_cerca): ?>
                <a href="/zumzeri/admin/bookings/camere.php" class="btn-admin-reset">Azzera</a>
            <?php endif; ?>
        </form>

        <!-- TABELLA -->
        <div class="admin-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Cliente</th>
                        <th>Camera</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Notti</th>
                        <th>Ospiti</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prenotazioni)): ?>
                        <tr>
                            <td colspan="9" class="admin-empty">Nessuna prenotazione trovata</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prenotazioni as $p):
                            $notti = (new DateTime($p['check_in']))->diff(new DateTime($p['check_out']))->days;
                        ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['codice']) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['nome'] . ' ' . $p['cognome']) ?></strong><br>
                                    <small><?= htmlspecialchars($p['email']) ?></small>
                                    <?php if ($p['telefono']): ?>
                                        <br><small><?= htmlspecialchars($p['telefono']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>Camera <?= htmlspecialchars($p['numero']) ?><br><small>Piano <?= $p['piano'] ?></small></td>
                                <td><?= date('d/m/Y', strtotime($p['check_in'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['check_out'])) ?></td>
                                <td><?= $notti ?></td>
                                <td><?= $p['ospiti'] ?></td>
                                <td><span class="stato-badge stato-<?= $p['stato'] ?>"><?= ucfirst(str_replace('_', ' ', $p['stato'])) ?></span></td>
                                <td>
                                    <form method="POST" class="form-stato">
                                        <input type="hidden" name="csrf_token" value="<?= genera_csrf() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <select name="stato" class="select-stato">
                                            <option value="in_attesa" <?= $p['stato'] === 'in_attesa'  ? 'selected' : '' ?>>In attesa</option>
                                            <option value="confermata" <?= $p['stato'] === 'confermata' ? 'selected' : '' ?>>Confermata</option>
                                            <option value="cancellata" <?= $p['stato'] === 'cancellata' ? 'selected' : '' ?>>Cancellata</option>
                                            <option value="completata" <?= $p['stato'] === 'completata' ? 'selected' : '' ?>>Completata</option>
                                        </select>
                                        <button type="submit" name="cambia_stato" class="btn-stato">Aggiorna</button>
                                    </form>
                                    <?php if ($p['note']): ?>
                                        <div class="nota-prenotazione">📝 <?= htmlspecialchars($p['note']) ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>

</html>