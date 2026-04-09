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
