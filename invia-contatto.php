<?php
require_once 'config/db.php';
require_once 'includes/mailer.php';

$errore  = '';
$successo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $oggetto  = trim($_POST['oggetto'] ?? '');
    $messaggio = trim($_POST['messaggio'] ?? '');

    if (!$nome || !$email || !$messaggio) {
        $errore = 'Compila tutti i campi obbligatori.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = 'Inserisci un indirizzo email valido.';
    } else {
        $inviata = invia_email_contatto([
            'nome'      => $nome,
            'email'     => $email,
            'oggetto'   => $oggetto,
            'messaggio' => $messaggio,
        ]);

        if ($inviata) {
            $successo = true;
        } else {
            $errore = 'Errore nell\'invio del messaggio. Riprova più tardi o contattaci direttamente.';
        }
    }
}

// Reindirizza alla pagina contatti con il risultato
if ($successo) {
    header('Location: /zumzeri/contatti.php?inviato=1');
} else {
    header('Location: /zumzeri/contatti.php?errore=' . urlencode($errore));
}
exit;
