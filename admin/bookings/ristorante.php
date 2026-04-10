<?php
require_once '../../admin/auth/session.php';
richiedi_login();
require_once '../../config/db.php';

// Cambio stato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambia_stato'])) {
    verifica_csrf();
    $id    = (int)$_POST['id'];
    $stato = $_POST['stato'];
    $stati_validi = ['in_attesa', 'confermata', 'cancellata'];
    if (in_array($stato, $stati_validi)) {
        $stmt = $pdo->prepare("SELECT stato FROM prenotazioni_ristorante WHERE id = ?");
        $stmt->execute([$id]);
        $stato_precedente = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE prenotazioni_ristorante SET stato = ? WHERE id = ?");
        $stmt->execute([$stato, $id]);

        if ($stato === 'cancellata' && $stato_precedente !== 'cancellata') {
            $stmt = $pdo->prepare("SELECT * FROM prenotazioni_ristorante WHERE id = ?");
            $stmt->execute([$id]);
            $prenotazione = $stmt->fetch();

            if ($prenotazione) {
                $giorni_ita = ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'];
                $giorno_num = (int)date('w', strtotime($prenotazione['giorno']));
                $data_leggibile = $giorni_ita[$giorno_num] . ' ' . date('d/m/Y', strtotime($prenotazione['giorno']));

                require_once '../../includes/mailer.php';
                invia_email_cancellazione_ristorante([
                    'nome'           => $prenotazione['nome'],
                    'cognome'        => $prenotazione['cognome'],
                    'email'          => $prenotazione['email'],
                    'codice'         => $prenotazione['codice'],
                    'data_leggibile' => $data_leggibile,
                    'turno'          => $prenotazione['turno'],
                    'persone'        => $prenotazione['persone'],
                ]);
            }
        }
    }
    header('Location: /zumzeri/admin/bookings/ristorante.php');
    exit;
}

// Filtri
$filtro_stato = $_GET['stato'] ?? '';
$filtro_cerca = trim($_GET['cerca'] ?? '');

$where = ['1=1'];
$params = [];

if ($filtro_stato) {
    $where[] = 'stato = ?';
    $params[] = $filtro_stato;
}

if ($filtro_cerca) {
    $where[] = '(codice LIKE ? OR nome LIKE ? OR cognome LIKE ? OR email LIKE ?)';
    $cerca = '%' . $filtro_cerca . '%';
    $params = array_merge($params, [$cerca, $cerca, $cerca, $cerca]);
}

$sql = "
    SELECT * FROM prenotazioni_ristorante
    WHERE " . implode(' AND ', $where) . "
    ORDER BY creata_il DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prenotazioni = $stmt->fetchAll();

$giorni_ita = ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'];

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="prenotazioni_ristorante_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM per Excel

    fputcsv($out, ['Codice', 'Nome', 'Cognome', 'Email', 'Telefono', 'Data', 'Turno', 'Persone', 'Stato', 'Note', 'Ricevuta il'], ';');

    foreach ($prenotazioni as $p) {
        $giorno_num = (int)date('w', strtotime($p['giorno']));
        $data_leggibile = $giorni_ita[$giorno_num] . ' ' . date('d/m/Y', strtotime($p['giorno']));
        fputcsv($out, [
            $p['codice'],
            $p['nome'],
            $p['cognome'],
            $p['email'],
            $p['telefono'] ?? '',
            $data_leggibile,
            ucfirst($p['turno']),
            $p['persone'],
            ucfirst(str_replace('_', ' ', $p['stato'])),
            $p['note'] ?? '',
            date('d/m/Y H:i', strtotime($p['creata_il'])),
        ], ';');
    }

    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazioni ristorante — Zum Zeri Admin</title>
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
            <a href="/zumzeri/admin/bookings/ristorante.php" class="active">Prenotazioni ristorante</a>
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
            <h1>Prenotazioni ristorante</h1>
            <a href="?stato=<?= urlencode($filtro_stato) ?>&cerca=<?= urlencode($filtro_cerca) ?>&export=csv"
                class="btn-admin-filtro" style="text-decoration:none;">↓ Esporta CSV</a>
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
            </select>
            <button type="submit" class="btn-admin-filtro">Filtra</button>
            <?php if ($filtro_stato || $filtro_cerca): ?>
                <a href="/zumzeri/admin/bookings/ristorante.php" class="btn-admin-reset">Azzera</a>
            <?php endif; ?>
        </form>

        <div style="font-size:12px; color:#aaa; margin-bottom:12px;">
            <?= count($prenotazioni) ?> prenotazioni
            <?php if ($filtro_stato || $filtro_cerca): ?>
                — il CSV esporterà solo i risultati filtrati
            <?php endif; ?>
        </div>

        <!-- TABELLA -->
        <div class="admin-section">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Turno</th>
                        <th>Persone</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prenotazioni)): ?>
                        <tr>
                            <td colspan="7" class="admin-empty">Nessuna prenotazione trovata</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prenotazioni as $p):
                            $giorno_num = (int)date('w', strtotime($p['giorno']));
                            $data_leggibile = $giorni_ita[$giorno_num] . ' ' . date('d/m/Y', strtotime($p['giorno']));
                        ?>
                            <tr>
                                <td><code><?= htmlspecialchars($p['codice']) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['nome'] . ' ' . $p['cognome']) ?></strong><br>
                                    <small><?= htmlspecialchars($p['email']) ?></small>
                                    <?php if ($p['telefono']): ?>
                                        <br><small><?= htmlspecialchars($p['telefono']) ?></small>
                                    <?php endif; ?>
                                    <?php if ($p['note']): ?>
                                        <br><small class="nota-prenotazione">📝 <?= htmlspecialchars($p['note']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= $data_leggibile ?></td>
                                <td><?= ucfirst($p['turno']) ?></td>
                                <td><?= $p['persone'] ?></td>
                                <td><span class="stato-badge stato-<?= $p['stato'] ?>"><?= ucfirst(str_replace('_', ' ', $p['stato'])) ?></span></td>
                                <td>
                                    <form method="POST" class="form-stato">
                                        <input type="hidden" name="csrf_token" value="<?= genera_csrf() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <select name="stato" class="select-stato">
                                            <option value="in_attesa" <?= $p['stato'] === 'in_attesa'  ? 'selected' : '' ?>>In attesa</option>
                                            <option value="confermata" <?= $p['stato'] === 'confermata' ? 'selected' : '' ?>>Confermata</option>
                                            <option value="cancellata" <?= $p['stato'] === 'cancellata' ? 'selected' : '' ?>>Cancellata</option>
                                        </select>
                                        <button type="submit" name="cambia_stato" class="btn-stato">Aggiorna</button>
                                    </form>
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