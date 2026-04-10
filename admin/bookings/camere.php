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
        $stmt = $pdo->prepare("SELECT stato FROM prenotazioni_camere WHERE id = ?");
        $stmt->execute([$id]);
        $stato_precedente = $stmt->fetchColumn();

        $stmt = $pdo->prepare("UPDATE prenotazioni_camere SET stato = ? WHERE id = ?");
        $stmt->execute([$stato, $id]);

        if ($stato === 'cancellata' && $stato_precedente !== 'cancellata') {
            $stmt = $pdo->prepare("
                SELECT pc.*, c.numero, c.piano
                FROM prenotazioni_camere pc
                JOIN camere c ON pc.camera_id = c.id
                WHERE pc.id = ?
            ");
            $stmt->execute([$id]);
            $prenotazione = $stmt->fetch();

            if ($prenotazione) {
                require_once '../../includes/mailer.php';
                invia_email_cancellazione_camera([
                    'nome'      => $prenotazione['nome'],
                    'cognome'   => $prenotazione['cognome'],
                    'email'     => $prenotazione['email'],
                    'codice'    => $prenotazione['codice'],
                    'numero'    => $prenotazione['numero'],
                    'piano'     => $prenotazione['piano'],
                    'check_in'  => $prenotazione['check_in'],
                    'check_out' => $prenotazione['check_out'],
                ]);
            }
        }

        if ($stato === 'confermata' && $stato_precedente !== 'confermata') {
            $stmt = $pdo->prepare("
                SELECT pc.*, c.numero, c.piano
                FROM prenotazioni_camere pc
                JOIN camere c ON pc.camera_id = c.id
                WHERE pc.id = ?
            ");
            $stmt->execute([$id]);
            $prenotazione = $stmt->fetch();

            if ($prenotazione) {
                require_once '../../includes/mailer.php';
                invia_email_conferma_definitiva_camera([
                    'nome'      => $prenotazione['nome'],
                    'cognome'   => $prenotazione['cognome'],
                    'email'     => $prenotazione['email'],
                    'codice'    => $prenotazione['codice'],
                    'numero'    => $prenotazione['numero'],
                    'piano'     => $prenotazione['piano'],
                    'check_in'  => $prenotazione['check_in'],
                    'check_out' => $prenotazione['check_out'],
                    'ospiti'    => $prenotazione['ospiti'],
                ]);
            }
        }
    }
    header('Location: /zumzeri/admin/bookings/camere.php?' . http_build_query(['stato' => $_GET['stato'] ?? '', 'cerca' => $_GET['cerca'] ?? '']));
    exit;
}

// Salva nota admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salva_nota'])) {
    verifica_csrf();
    $id         = (int)$_POST['id'];
    $nota_admin = trim($_POST['nota_admin'] ?? '');
    $stmt = $pdo->prepare("UPDATE prenotazioni_camere SET note_admin = ? WHERE id = ?");
    $stmt->execute([$nota_admin, $id]);
    header('Location: /zumzeri/admin/bookings/camere.php?' . http_build_query(['stato' => $_GET['stato'] ?? '', 'cerca' => $_GET['cerca'] ?? '']));
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

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="prenotazioni_camere_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

    fputcsv($out, ['Codice', 'Nome', 'Cognome', 'Email', 'Telefono', 'Camera', 'Piano', 'Check-in', 'Check-out', 'Notti', 'Ospiti', 'Stato', 'Note cliente', 'Note admin', 'Ricevuta il'], ';');

    foreach ($prenotazioni as $p) {
        $notti = (new DateTime($p['check_in']))->diff(new DateTime($p['check_out']))->days;
        fputcsv($out, [
            $p['codice'],
            $p['nome'],
            $p['cognome'],
            $p['email'],
            $p['telefono'] ?? '',
            'Camera ' . $p['numero'],
            'Piano ' . $p['piano'],
            date('d/m/Y', strtotime($p['check_in'])),
            date('d/m/Y', strtotime($p['check_out'])),
            $notti,
            $p['ospiti'],
            ucfirst(str_replace('_', ' ', $p['stato'])),
            $p['note'] ?? '',
            $p['note_admin'] ?? '',
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
    <title>Prenotazioni camere — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
    <style>
        .nota-admin-form {
            margin-top: 10px;
        }

        .nota-admin-label {
            font-size: 10px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--colore-legno);
            margin-bottom: 4px;
            display: block;
        }

        .nota-admin-form textarea {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #e8d5b0;
            border-radius: 2px;
            font-size: 12px;
            font-family: var(--font-testo);
            color: var(--colore-terra);
            resize: vertical;
            min-height: 52px;
            background: #fffef9;
        }

        .nota-admin-form textarea:focus {
            outline: none;
            border-color: var(--colore-legno);
        }

        .btn-nota {
            font-size: 11px;
            padding: 3px 10px;
            background: var(--colore-sabbia);
            border: 1px solid #e8d5b0;
            border-radius: 2px;
            cursor: pointer;
            font-family: var(--font-testo);
            color: var(--colore-terra);
            margin-top: 4px;
        }

        .btn-nota:hover {
            background: #e8d5b0;
        }
    </style>
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php" class="active">Prenotazioni camere</a>
            <a href="/zumzeri/admin/calendario.php">Calendario camere</a>
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
            <a href="?stato=<?= urlencode($filtro_stato) ?>&cerca=<?= urlencode($filtro_cerca) ?>&export=csv"
                class="btn-admin-filtro" style="text-decoration:none;">↓ Esporta CSV</a>
        </div>

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

        <div style="font-size:12px; color:#aaa; margin-bottom:12px;">
            <?= count($prenotazioni) ?> prenotazioni
            <?php if ($filtro_stato || $filtro_cerca): ?> — il CSV esporterà solo i risultati filtrati<?php endif; ?>
        </div>

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
                        <th>Azioni e note</th>
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
                                    <?php if ($p['telefono']): ?><br><small><?= htmlspecialchars($p['telefono']) ?></small><?php endif; ?>
                                    <?php if ($p['note']): ?><br><small class="nota-prenotazione">📝 <?= htmlspecialchars($p['note']) ?></small><?php endif; ?>
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

                                    <form method="POST" class="nota-admin-form">
                                        <input type="hidden" name="csrf_token" value="<?= genera_csrf() ?>">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <span class="nota-admin-label">🔒 Nota interna</span>
                                        <textarea name="nota_admin" rows="2" placeholder="Visibile solo dallo staff..."><?= htmlspecialchars($p['note_admin'] ?? '') ?></textarea>
                                        <button type="submit" name="salva_nota" class="btn-nota">Salva nota</button>
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