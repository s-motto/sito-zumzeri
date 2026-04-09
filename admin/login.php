<?php
session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /zumzeri/admin/index.php');
    exit;
}

require_once '../config/db.php';

$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $errore = 'Inserisci username e password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = ?");
        $stmt->execute([$username]);
        $utente = $stmt->fetch();

        if ($utente && password_verify($password, $utente['password'])) {
            $_SESSION['admin_id']   = $utente['id'];
            $_SESSION['admin_nome'] = $utente['nome'];
            header('Location: /zumzeri/admin/index.php');
            exit;
        } else {
            $errore = 'Username o password non corretti.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Zum Zeri Admin</title>
    <link rel="stylesheet" href="/zumzeri/assets/css/style.css">
    <style>
        body {
            background: var(--colore-sabbia);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-box {
            background: #fff;
            border: 1px solid #e8d5b0;
            border-radius: 4px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            font-family: var(--font-titoli);
            font-size: 24px;
            color: var(--colore-terra);
            text-align: center;
            margin-bottom: 4px;
        }

        .login-sub {
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #aaa;
            text-align: center;
            margin-bottom: 32px;
        }

        .login-box .form-gruppo {
            margin-bottom: 16px;
        }

        .login-box label {
            display: block;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 6px;
        }

        .login-box input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e8d5b0;
            border-radius: 2px;
            font-size: 14px;
            font-family: var(--font-testo);
            box-sizing: border-box;
        }

        .login-box input:focus {
            outline: none;
            border-color: var(--colore-legno);
        }

        .login-btn {
            width: 100%;
            background: var(--colore-legno);
            color: var(--colore-crema);
            border: none;
            padding: 12px;
            font-size: 13px;
            font-family: var(--font-testo);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 2px;
            margin-top: 8px;
        }

        .login-btn:hover {
            background: var(--colore-terra);
        }
    </style>
</head>

<body>
    <div class="login-box">
        <div class="login-logo">Zum Zeri</div>
        <div class="login-sub">Pannello amministratore</div>

        <?php if ($errore): ?>
            <div class="alert alert--errore" style="margin-bottom: 20px;"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-gruppo">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    autocomplete="username" required>
            </div>
            <div class="form-gruppo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                    autocomplete="current-password" required>
            </div>
            <button type="submit" class="login-btn">Accedi</button>
        </form>
    </div>
</body>

</html>