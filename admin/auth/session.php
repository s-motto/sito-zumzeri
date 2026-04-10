<?php
function richiedi_login(): void
{
    session_start();
    if (empty($_SESSION['admin_id'])) {
        header('Location: /zumzeri/admin/login.php');
        exit;
    }
}

function logout(): void
{
    session_start();
    session_destroy();
    header('Location: /zumzeri/admin/login.php');
    exit;
}

function genera_csrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifica_csrf(): void
{
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Richiesta non valida.');
    }
    // Rigenera il token dopo ogni uso
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
