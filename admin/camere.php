<?php
require_once '../admin/auth/session.php';
richiedi_login();
require_once '../config/db.php';

$messaggio = '';
$errore = '';

// Modifica camera
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salva_camera'])) {
    $id     = (int)$_POST['id'];
    $prezzo = (float)str_replace(',', '.', $_POST['prezzo'] ?? 0);
    $stato  = $_POST['stato'] ?? 'disponibile';
    $note   = trim($_POST['note'] ?? '');

    $stati_validi = ['disponibile', 'inagibile', 'manutenzione'];
    if (in_array($stato, $stati_validi) && $prezzo >= 0) {
        $stmt = $pdo->prepare("
            UPDATE camere SET prezzo = ?, stato = ?, note = ? WHERE id = ?
        ");
        $stmt->execute([$prezzo, $stato, $note, $id]);
        $messaggio = 'Camera aggiornata correttamente.';
    } else {
        $errore = 'Dati non validi.';
    }
}

// Carica camere
$stmt = $pdo->query("SELECT * FROM camere ORDER BY piano ASC, numero ASC");
$camere = $stmt->fetchAll();

// Raggruppa per piano
$per_piano = [];
foreach ($camere as $camera) {
    $per_piano[$camera['piano']][] = $camera;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione camere — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php">Prenotazioni camere</a>
            <a href="/zumzeri/admin/bookings/ristorante.php">Prenotazioni ristorante</a>
            <a href="/zumzeri/admin/camere.php" class="active">Gestione camere</a>
            <a href="/zumzeri/admin/impostazioni.php">Impostazioni</a>
        </nav>
        <div class="admin-footer">
            <span><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>
            <a href="/zumzeri/admin/logout.php">Esci</a>
        </div>
    </aside>

    <main class="admin-main">
        <div class="admin-header">
            <h1>Gestione camere</h1>
            <span class="admin-date"><?= count($camere) ?> camere</span>
        </div>

        <?php if ($messaggio): ?>
            <div class="alert alert--ok" style="margin-bottom: 24px;"><?= htmlspecialchars($messaggio) ?></div>
        <?php endif; ?>
        <?php if ($errore): ?>
            <div class="alert alert--errore" style="margin-bottom: 24px;"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>

        <?php foreach ($per_piano as $piano => $camere_piano): ?>
            <div class="admin-section" style="margin-bottom: 24px;">
                <div class="admin-section-header">
                    <h2>Piano <?= $piano ?></h2>
                    <span style="font-size:12px; color:#aaa;"><?= count($camere_piano) ?> camere</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Camera</th>
                            <th>Posti</th>
                            <th>Prezzo/notte</th>
                            <th>Stato</th>
                            <th>Note</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($camere_piano as $camera): ?>
                            <tr>
                                <td><strong>Camera <?= htmlspecialchars($camera['numero']) ?></strong></td>
                                <td><?= $camera['posti'] ?> posti</td>
                                <td>
                                    <form method="POST" class="form-stato" style="gap:4px;">
                                        <input type="hidden" name="id" value="<?= $camera['id'] ?>">
                                        <div style="display:flex; flex-direction:column; gap:6px;">
                                            <div style="display:flex; align-items:center; gap:6px;">
                                                <span style="font-size:12px; color:#aaa;">€</span>
                                                <input type="text" name="prezzo"
                                                    value="<?= number_format((float)($camera['prezzo'] ?? 0), 2, ',', '') ?>"
                                                    class="imp-input-num" style="width:70px;">
                                            </div>
                                            <select name="stato" class="select-stato">
                                                <option value="disponibile" <?= $camera['stato'] === 'disponibile'  ? 'selected' : '' ?>>Disponibile</option>
                                                <option value="manutenzione" <?= $camera['stato'] === 'manutenzione' ? 'selected' : '' ?>>Manutenzione</option>
                                                <option value="inagibile" <?= $camera['stato'] === 'inagibile'    ? 'selected' : '' ?>>Inagibile</option>
                                            </select>
                                            <input type="text" name="note"
                                                value="<?= htmlspecialchars($camera['note'] ?? '') ?>"
                                                placeholder="Note..."
                                                style="padding:4px 8px; border:1px solid #e8d5b0; border-radius:2px; font-size:12px; font-family:var(--font-testo); width:160px;">
                                            <button type="submit" name="salva_camera" class="btn-stato">Salva</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <span class="stato-badge stato-<?= $camera['stato'] ?>">
                                        <?= ucfirst($camera['stato']) ?>
                                    </span>
                                </td>
                                <td style="font-size:12px; color:#aaa; font-style:italic;">
                                    <?= htmlspecialchars($camera['note'] ?? '—') ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

    </main>

</body>

</html>