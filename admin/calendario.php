<?php
require_once '../admin/auth/session.php';
richiedi_login();
require_once '../config/db.php';

// Mese visualizzato
$mese  = (int)($_GET['mese'] ?? date('n'));
$anno  = (int)($_GET['anno'] ?? date('Y'));

// Normalizza
if ($mese < 1) {
    $mese = 12;
    $anno--;
}
if ($mese > 12) {
    $mese = 1;
    $anno++;
}

$primo_giorno = mktime(0, 0, 0, $mese, 1, $anno);
$giorni_nel_mese = (int)date('t', $primo_giorno);
$mese_prec = $mese === 1  ? ['mese' => 12, 'anno' => $anno - 1] : ['mese' => $mese - 1, 'anno' => $anno];
$mese_succ = $mese === 12 ? ['mese' => 1,  'anno' => $anno + 1] : ['mese' => $mese + 1, 'anno' => $anno];

$mesi_ita = [
    '',
    'Gennaio',
    'Febbraio',
    'Marzo',
    'Aprile',
    'Maggio',
    'Giugno',
    'Luglio',
    'Agosto',
    'Settembre',
    'Ottobre',
    'Novembre',
    'Dicembre'
];
$giorni_ita = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];

// Carica camere
$stmt = $pdo->query("SELECT * FROM camere ORDER BY piano ASC, numero ASC");
$camere = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Carica prenotazioni del mese (con margine per prenotazioni che sconfinano)
$data_inizio = sprintf('%04d-%02d-01', $anno, $mese);
$data_fine   = sprintf('%04d-%02d-%02d', $anno, $mese, $giorni_nel_mese);

$stmt = $pdo->prepare("
    SELECT pc.camera_id, pc.check_in, pc.check_out, pc.stato,
           pc.nome, pc.cognome, pc.codice
    FROM prenotazioni_camere pc
    WHERE pc.stato NOT IN ('cancellata')
      AND pc.check_in  <= ?
      AND pc.check_out >  ?
    ORDER BY pc.check_in
");
$stmt->execute([$data_fine, $data_inizio]);
$prenotazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Indicizza per camera_id e giorno
// $mappa[camera_id][giorno] = ['stato', 'nome', 'cognome', 'codice', 'primo' (primo giorno del soggiorno nel mese)]
$mappa = [];
foreach ($prenotazioni as $p) {
    $cid = $p['camera_id'];
    $ci  = max(strtotime($p['check_in']),  $primo_giorno);
    $co  = min(strtotime($p['check_out']), mktime(0, 0, 0, $mese, $giorni_nel_mese + 1, $anno));

    for ($ts = $ci; $ts < $co; $ts = strtotime('+1 day', $ts)) {
        $g = (int)date('j', $ts);
        $mappa[$cid][$g] = [
            'stato'   => $p['stato'],
            'nome'    => $p['nome'],
            'cognome' => $p['cognome'],
            'codice'  => $p['codice'],
            'primo'   => ($ts === $ci),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario camere — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <link rel="stylesheet" href="/zumzeri/assets/css/admin.css">
    <style>
        .cal-nav {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .cal-nav h2 {
            font-family: var(--font-titoli);
            font-size: 20px;
            font-weight: 400;
            color: var(--colore-terra);
            min-width: 180px;
            text-align: center;
        }

        .cal-nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #e8d5b0;
            border-radius: 2px;
            color: var(--colore-terra);
            font-size: 16px;
            transition: background 0.15s;
        }

        .cal-nav a:hover {
            background: var(--colore-sabbia);
        }

        .cal-oggi {
            font-size: 12px;
            color: var(--colore-legno);
            border: 1px solid var(--colore-legno);
            padding: 4px 12px;
            border-radius: 2px;
        }

        .cal-oggi:hover {
            background: var(--colore-sabbia);
        }

        /* GRIGLIA */
        .cal-wrap {
            overflow-x: auto;
            border: 1px solid #e8d5b0;
            border-radius: 4px;
            background: #fff;
        }

        .cal-table {
            border-collapse: collapse;
            min-width: 100%;
            font-size: 12px;
        }

        /* INTESTAZIONE GIORNI */
        .cal-table thead th {
            position: sticky;
            top: 0;
            background: var(--colore-sabbia);
            border-bottom: 1px solid #e8d5b0;
            border-right: 1px solid #f0e8d8;
            padding: 0;
            z-index: 10;
            min-width: 36px;
            width: 36px;
        }

        .cal-table thead th:first-child {
            position: sticky;
            left: 0;
            z-index: 20;
            min-width: 110px;
            width: 110px;
            background: var(--colore-sabbia);
            border-right: 2px solid #e8d5b0;
        }

        .th-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6px 2px;
            line-height: 1.3;
        }

        .th-dow {
            font-size: 9px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .th-num {
            font-size: 13px;
            font-weight: 600;
            color: var(--colore-terra);
        }

        .th-inner--oggi .th-num {
            color: var(--colore-legno);
        }

        .th-inner--weekend .th-dow {
            color: #c9847a;
        }

        .th-inner--weekend .th-num {
            color: #c9847a;
        }

        /* RIGHE CAMERE */
        .cal-table tbody tr {
            border-bottom: 1px solid #f0e8d8;
        }

        .cal-table tbody tr:last-child {
            border-bottom: none;
        }

        .cal-table tbody tr:hover td {
            background-color: #fdf8f4;
        }

        .cal-table tbody tr:hover td:first-child {
            background-color: #fdf8f4;
        }

        /* CELLA CAMERA */
        .td-camera {
            position: sticky;
            left: 0;
            background: #fff;
            border-right: 2px solid #e8d5b0;
            padding: 8px 12px;
            z-index: 5;
            white-space: nowrap;
        }

        .td-camera strong {
            display: block;
            font-size: 12px;
            color: var(--colore-terra);
        }

        .td-camera span {
            font-size: 10px;
            color: #aaa;
        }

        /* SEPARATORE PIANO */
        .tr-piano td {
            background: var(--colore-sabbia) !important;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
            padding: 4px 12px;
            font-weight: 600;
            border-bottom: 1px solid #e8d5b0;
        }

        /* CELLE GIORNO */
        .td-giorno {
            border-right: 1px solid #f0e8d8;
            padding: 0;
            height: 44px;
            vertical-align: middle;
            position: relative;
        }

        .td-giorno--libera {
            background: #fff;
        }

        .td-giorno--weekend {
            background: #fdf8f4;
        }

        .td-giorno--oggi {
            background: #fffbf0 !important;
        }

        .td-giorno--inagibile {
            background: repeating-linear-gradient(45deg, #f5f5f5, #f5f5f5 4px, #ebebeb 4px, #ebebeb 8px);
        }

        /* BLOCCO PRENOTAZIONE */
        .cell-prenota {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 6px;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: default;
        }

        .cell-prenota--in_attesa {
            background: #fff8e1;
            color: #b45309;
        }

        .cell-prenota--confermata {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .cell-prenota--completata {
            background: #e8eaf6;
            color: #3949ab;
        }

        /* LEGENDA */
        .cal-legenda {
            display: flex;
            gap: 20px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .legenda-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #666;
        }

        .legenda-dot {
            width: 14px;
            height: 14px;
            border-radius: 2px;
            flex-shrink: 0;
        }
    </style>
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="admin-logo">Zum Zeri</div>
        <nav class="admin-nav">
            <a href="/zumzeri/admin/index.php">Dashboard</a>
            <a href="/zumzeri/admin/bookings/camere.php">Prenotazioni camere</a>
            <a href="/zumzeri/admin/calendario.php" class="active">Calendario camere</a>
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
            <h1>Calendario camere</h1>
        </div>

        <!-- NAVIGAZIONE MESE -->
        <div class="cal-nav">
            <a href="?mese=<?= $mese_prec['mese'] ?>&anno=<?= $mese_prec['anno'] ?>">&#8249;</a>
            <h2><?= $mesi_ita[$mese] ?> <?= $anno ?></h2>
            <a href="?mese=<?= $mese_succ['mese'] ?>&anno=<?= $mese_succ['anno'] ?>">&#8250;</a>
            <a href="?mese=<?= date('n') ?>&anno=<?= date('Y') ?>" class="cal-oggi">Oggi</a>
        </div>

        <!-- GRIGLIA -->
        <div class="cal-wrap">
            <table class="cal-table">
                <thead>
                    <tr>
                        <th>
                            <div class="th-inner"></div>
                        </th>
                        <?php for ($g = 1; $g <= $giorni_nel_mese; $g++):
                            $ts  = mktime(0, 0, 0, $mese, $g, $anno);
                            $dow = (int)date('w', $ts);
                            $oggi = ($anno == date('Y') && $mese == date('n') && $g == date('j'));
                            $weekend = ($dow === 0 || $dow === 6);
                        ?>
                            <th>
                                <div class="th-inner <?= $oggi ? 'th-inner--oggi' : '' ?> <?= $weekend ? 'th-inner--weekend' : '' ?>">
                                    <span class="th-dow"><?= $giorni_ita[$dow] ?></span>
                                    <span class="th-num"><?= $g ?></span>
                                </div>
                            </th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $piano_corrente = null;
                    foreach ($camere as $camera):
                        // Separatore piano
                        if ($camera['piano'] !== $piano_corrente):
                            $piano_corrente = $camera['piano'];
                    ?>
                            <tr class="tr-piano">
                                <td colspan="<?= $giorni_nel_mese + 1 ?>">Piano <?= $piano_corrente ?></td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td class="td-camera">
                                <strong>Camera <?= htmlspecialchars($camera['numero']) ?></strong>
                                <span><?= $camera['posti'] ?> posti</span>
                            </td>

                            <?php for ($g = 1; $g <= $giorni_nel_mese; $g++):
                                $ts      = mktime(0, 0, 0, $mese, $g, $anno);
                                $dow     = (int)date('w', $ts);
                                $oggi    = ($anno == date('Y') && $mese == date('n') && $g == date('j'));
                                $weekend = ($dow === 0 || $dow === 6);
                                $cid     = $camera['id'];
                                $inagibile = ($camera['stato'] === 'inagibile');
                                $prenota = $mappa[$cid][$g] ?? null;

                                $classe_td = 'td-giorno';
                                if ($inagibile)    $classe_td .= ' td-giorno--inagibile';
                                elseif ($oggi)     $classe_td .= ' td-giorno--oggi';
                                elseif ($weekend)  $classe_td .= ' td-giorno--weekend';
                                else               $classe_td .= ' td-giorno--libera';
                            ?>
                                <td class="<?= $classe_td ?>">
                                    <?php if ($prenota): ?>
                                        <div class="cell-prenota cell-prenota--<?= $prenota['stato'] ?>"
                                            title="<?= htmlspecialchars($prenota['nome'] . ' ' . $prenota['cognome']) ?> [<?= $prenota['codice'] ?>]">
                                            <?php if ($prenota['primo']): ?>
                                                <?= htmlspecialchars($prenota['cognome']) ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- LEGENDA -->
        <div class="cal-legenda">
            <div class="legenda-item">
                <div class="legenda-dot" style="background:#fff; border:1px solid #e8d5b0;"></div>
                <span>Libera</span>
            </div>
            <div class="legenda-item">
                <div class="legenda-dot" style="background:#e8f5e9; border:1px solid #a5d6a7;"></div>
                <span>Confermata</span>
            </div>
            <div class="legenda-item">
                <div class="legenda-dot" style="background:#fff8e1; border:1px solid #ffe082;"></div>
                <span>In attesa</span>
            </div>
            <div class="legenda-item">
                <div class="legenda-dot" style="background:#e8eaf6; border:1px solid #9fa8da;"></div>
                <span>Completata</span>
            </div>
            <div class="legenda-item">
                <div class="legenda-dot" style="background: repeating-linear-gradient(45deg,#f5f5f5,#f5f5f5 4px,#ebebeb 4px,#ebebeb 8px);"></div>
                <span>Inagibile</span>
            </div>
        </div>

    </main>

</body>

</html>