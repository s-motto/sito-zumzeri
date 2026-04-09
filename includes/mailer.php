<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function invia_email_conferma_camera(array $dati): bool
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAILTRAP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAILTRAP_USERNAME'];
    $mail->Password   = $_ENV['MAILTRAP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $_ENV['MAILTRAP_PORT'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
    $mail->addAddress($dati['email'], $dati['nome'] . ' ' . $dati['cognome']);
    $mail->addReplyTo($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);

    $mail->isHTML(true);
    $mail->Subject = 'Prenotazione ricevuta — Zum Zeri [' . $dati['codice'] . ']';
    $mail->Body    = email_body_camera($dati);
    $mail->AltBody = email_testo_camera($dati);

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log('Errore email: ' . $mail->ErrorInfo);
    return false;
  }
}

function email_body_camera(array $d): string
{
  $check_in  = date('d/m/Y', strtotime($d['check_in']));
  $check_out = date('d/m/Y', strtotime($d['check_out']));
  $notti     = (new DateTime($d['check_in']))->diff(new DateTime($d['check_out']))->days;

  return "
    <!DOCTYPE html>
    <html lang='it'>
    <head><meta charset='UTF-8'></head>
    <body style='font-family: Georgia, serif; color: #3d2e10; max-width: 600px; margin: 0 auto; padding: 0;'>

      <div style='background: #2C2C2A; padding: 32px; text-align: center;'>
        <h1 style='color: #FAEEDA; font-size: 28px; font-weight: 400; margin: 0;'>Zum Zeri</h1>
        <p style='color: rgba(255,255,255,0.5); font-size: 12px; letter-spacing: 2px; margin: 8px 0 0;'>PASSO DEI DUE SANTI · ZERI (MS)</p>
      </div>

      <div style='padding: 40px 32px;'>
        <h2 style='font-size: 22px; font-weight: 400; margin: 0 0 8px;'>
          Ciao {$d['nome']},
        </h2>
        <p style='color: #666; line-height: 1.7; margin: 0 0 32px;'>
          Abbiamo ricevuto la tua richiesta di prenotazione. Ti contatteremo a breve per la conferma definitiva.
        </p>

        <div style='background: #F1EFE8; border-radius: 4px; padding: 24px; margin-bottom: 32px;'>
          <p style='font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #aaa; margin: 0 0 8px;'>Il tuo codice prenotazione</p>
          <p style='font-size: 36px; letter-spacing: 6px; color: #3d2e10; margin: 0 0 8px;'>{$d['codice']}</p>
          <p style='font-size: 12px; color: #aaa; margin: 0;'>Conserva questo codice per qualsiasi comunicazione con noi</p>
        </div>

        <table style='width: 100%; border-collapse: collapse; margin-bottom: 32px;'>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Camera</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$d['numero']} — Piano {$d['piano']}</td>
          </tr>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Arrivo</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$check_in}</td>
          </tr>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Partenza</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$check_out}</td>
          </tr>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Notti</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$notti}</td>
          </tr>
          <tr>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Ospiti</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$d['ospiti']}</td>
          </tr>
        </table>

        <p style='color: #666; font-size: 14px; line-height: 1.7;'>
          Per qualsiasi informazione puoi rispondere a questa email o contattarci al numero che trovi sul sito.
        </p>
      </div>

      <div style='background: #F1EFE8; padding: 24px 32px; text-align: center; border-top: 1px solid #e8d5b0;'>
        <p style='font-size: 12px; color: #aaa; margin: 0;'>
          Zum Zeri · Passo dei Due Santi · 54029 Zeri (MS)<br>
          <a href='https://www.zumzeri.it' style='color: #854F0B;'>www.zumzeri.it</a>
        </p>
      </div>

    </body>
    </html>
    ";
}

function email_testo_camera(array $d): string
{
  $check_in  = date('d/m/Y', strtotime($d['check_in']));
  $check_out = date('d/m/Y', strtotime($d['check_out']));
  return "
Ciao {$d['nome']},

abbiamo ricevuto la tua richiesta di prenotazione. Ti contatteremo a breve per la conferma definitiva.

CODICE PRENOTAZIONE: {$d['codice']}

Camera: {$d['numero']} — Piano {$d['piano']}
Arrivo: {$check_in}
Partenza: {$check_out}
Ospiti: {$d['ospiti']}

Zum Zeri · Passo dei Due Santi · 54029 Zeri (MS)
    ";
}

function invia_email_conferma_ristorante(array $dati): bool
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAILTRAP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAILTRAP_USERNAME'];
    $mail->Password   = $_ENV['MAILTRAP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $_ENV['MAILTRAP_PORT'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
    $mail->addAddress($dati['email'], $dati['nome'] . ' ' . $dati['cognome']);
    $mail->addReplyTo($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);

    $mail->isHTML(true);
    $mail->Subject = 'Prenotazione ristorante — Zum Zeri [' . $dati['codice'] . ']';
    $mail->Body    = email_body_ristorante($dati);
    $mail->AltBody = email_testo_ristorante($dati);

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log('Errore email ristorante: ' . $mail->ErrorInfo);
    return false;
  }
}

function email_body_ristorante(array $d): string
{
  return "
    <!DOCTYPE html>
    <html lang='it'>
    <head><meta charset='UTF-8'></head>
    <body style='font-family: Georgia, serif; color: #3d2e10; max-width: 600px; margin: 0 auto;'>

      <div style='background: #2C2C2A; padding: 32px; text-align: center;'>
        <h1 style='color: #FAEEDA; font-size: 28px; font-weight: 400; margin: 0;'>Zum Zeri</h1>
        <p style='color: rgba(255,255,255,0.5); font-size: 12px; letter-spacing: 2px; margin: 8px 0 0;'>RIFUGIO FAGGIO CROCIATO</p>
      </div>

      <div style='padding: 40px 32px;'>
        <h2 style='font-size: 22px; font-weight: 400; margin: 0 0 8px;'>Ciao {$d['nome']},</h2>
        <p style='color: #666; line-height: 1.7; margin: 0 0 32px;'>
          Abbiamo ricevuto la tua richiesta di prenotazione al ristorante. Ti contatteremo a breve per la conferma definitiva.
        </p>

        <div style='background: #F1EFE8; border-radius: 4px; padding: 24px; margin-bottom: 32px;'>
          <p style='font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #aaa; margin: 0 0 8px;'>Il tuo codice prenotazione</p>
          <p style='font-size: 36px; letter-spacing: 6px; color: #3d2e10; margin: 0 0 8px;'>{$d['codice']}</p>
          <p style='font-size: 12px; color: #aaa; margin: 0;'>Conserva questo codice per qualsiasi comunicazione con noi</p>
        </div>

        <table style='width: 100%; border-collapse: collapse; margin-bottom: 32px;'>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Data</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$d['data_leggibile']}</td>
          </tr>
          <tr style='border-bottom: 1px solid #e8d5b0;'>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Turno</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>" . ucfirst($d['turno']) . "</td>
          </tr>
          <tr>
            <td style='padding: 12px 0; color: #888; font-size: 14px;'>Persone</td>
            <td style='padding: 12px 0; font-size: 14px; text-align: right;'>{$d['persone']}</td>
          </tr>
        </table>

        <p style='color: #666; font-size: 14px; line-height: 1.7;'>
          Per qualsiasi informazione puoi rispondere a questa email o contattarci al numero che trovi sul sito.
        </p>
      </div>

      <div style='background: #F1EFE8; padding: 24px 32px; text-align: center; border-top: 1px solid #e8d5b0;'>
        <p style='font-size: 12px; color: #aaa; margin: 0;'>
          Zum Zeri · Passo dei Due Santi · 54029 Zeri (MS)<br>
          <a href='https://www.zumzeri.it' style='color: #854F0B;'>www.zumzeri.it</a>
        </p>
      </div>

    </body>
    </html>
    ";
}

function email_testo_ristorante(array $d): string
{
  return "
Ciao {$d['nome']},

abbiamo ricevuto la tua richiesta di prenotazione al ristorante. Ti contatteremo a breve per la conferma definitiva.

CODICE PRENOTAZIONE: {$d['codice']}

Data: {$d['data_leggibile']}
Turno: " . ucfirst($d['turno']) . "
Persone: {$d['persone']}

Zum Zeri · Passo dei Due Santi · 54029 Zeri (MS)
    ";
}
