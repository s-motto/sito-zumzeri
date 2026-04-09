<?php
// Configurazione del database
define('DB_HOST', 'localhost');
define('DB_NAME', 'zumzeri');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Connessione al database usando PDO(PHP Data Objects)
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Imposta il modo di gestione degli errori
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Imposta il modo di fetch predefinito
            PDO::ATTR_EMULATE_PREPARES   => false, // Disabilita l'emulazione delle query preparate per una maggiore sicurezza
        ]
    );
} catch (PDOException $e) { // Gestione degli errori di connessione
    die('Errore di connessione: ' . $e->getMessage());
}
