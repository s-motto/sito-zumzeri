# Zum Zeri — Sito web

Progetto in sviluppo per il sito ufficiale di **Zum Zeri**, rifugio e hotel al Passo dei Due Santi, Zeri (MS) — Lunigiana, 1400 m s.l.m.

> ⚠️ Work in progress — il sito non è ancora in produzione. Mancano ancora immagini definitive, contatti reali e la configurazione dell'hosting.

---

## Tecnologie utilizzate

- **PHP 8.x** — logica server-side e generazione pagine
- **MySQL** — database prenotazioni e impostazioni
- **HTML5 / CSS3** — struttura e stile
- **JavaScript** — toggle stagionale, hamburger menu
- **PHPMailer** — invio email di conferma prenotazioni
- **Composer** — gestione dipendenze PHP

---

## Funzionalità

### Sito pubblico

- Home con toggle estate/inverno
- Pagina Gran Baita Lunigiana (hotel 23 camere)
- Pagina Rifugio Faggio Crociato (ristorante e bar)
- Pagina Attività con stato impianti sci in tempo reale
- Webcam live (stream YouTube)
- Pagina Contatti con mappa Google integrata
- Responsive mobile con hamburger menu

### Sistema prenotazioni

- Ricerca disponibilità camere per date e numero ospiti
- Prenotazione tavoli ristorante (sabato/domenica, verifica coperti)
- Email di conferma automatica con codice univoco (PHPMailer)
- Blocco automatico in modalità self-service invernale

### Pannello amministratore

- Login protetto con sessione PHP e password hashata (bcrypt)
- Dashboard con statistiche prenotazioni del giorno
- Gestione prenotazioni camere e ristorante (lista, filtri, cambio stato)
- Gestione camere (prezzi, stato, note)
- Impostazioni globali: stagione, modalità self-service, coperti massimi

---

## Struttura del progetto

```
zumzeri/
├── admin/                  # Pannello amministratore
│   ├── auth/session.php    # Gestione sessioni
│   ├── bookings/           # Gestione prenotazioni
│   ├── camere.php          # Gestione camere
│   ├── impostazioni.php    # Impostazioni globali
│   ├── index.php           # Dashboard
│   └── login.php           # Login admin
├── assets/
│   ├── css/                # Fogli di stile (divisi per sezione)
│   ├── img/                # Immagini e loghi
│   └── js/main.js          # JavaScript
├── config/
│   └── db.php              # Connessione PDO al database
├── includes/
│   ├── footer.php          # Footer riutilizzabile
│   ├── header.php          # Header e navbar riutilizzabili
│   └── mailer.php          # Funzioni invio email (PHPMailer)
├── vendor/                 # Dipendenze Composer (non committato)
├── .env                    # Credenziali SMTP (non committato)
├── .env.example            # Template credenziali
├── .gitignore
├── composer.json
├── robots.txt
├── sitemap.xml
├── index.php               # Home
├── gran-baita.php          # Pagina hotel
├── rifugio.php             # Pagina ristorante
├── attivita.php            # Pagina attività
├── webcam.php              # Pagina webcam
├── contatti.php            # Pagina contatti
├── prenota.php             # Prenotazione camere
├── prenota-camera.php      # Conferma prenotazione camera
├── prenota-ristorante.php  # Prenotazione ristorante
├── prenota-ristorante-conferma.php
├── invia-contatto.php      # Gestione form contatti
└── privacy.php             # Privacy policy
```

---

## Installazione in locale

### Requisiti

- XAMPP (o equivalente con PHP 8.x e MySQL)
- Composer

### Procedura

**1. Clona il repository**

```bash
git clone https://github.com/s-motto/zumzeri.git
cd zumzeri
```

**2. Installa le dipendenze**

```bash
composer install
```

**3. Configura le variabili d'ambiente**

Copia `.env.example` in `.env` e compila con i tuoi dati:

```
MAILTRAP_HOST=sandbox.smtp.mailtrap.io
MAILTRAP_PORT=2525
MAILTRAP_USERNAME=il_tuo_username
MAILTRAP_PASSWORD=la_tua_password
MAIL_FROM=info@zumzeri.it
MAIL_FROM_NAME="Zum Zeri"
```

Per testare le email in locale usa [Mailtrap](https://mailtrap.io).

**4. Crea il database**

Apri phpMyAdmin, crea un database chiamato `zumzeri` con collazione `utf8mb4_unicode_ci`, poi importa il file SQL con la struttura delle tabelle.

**5. Crea l'utente admin**

Crea un file temporaneo `genera-hash.php` nella root:

```php
<?php
echo password_hash('la_tua_password', PASSWORD_DEFAULT);
```

Vai su `http://localhost/zumzeri/genera-hash.php`, copia l'hash e inseriscilo nel database:

```sql
INSERT INTO utenti (username, password, nome)
VALUES ('admin', 'HASH_COPIATO', 'Amministratore');
```

Elimina subito `genera-hash.php`.

**6. Avvia il server**

Avvia Apache e MySQL da XAMPP e vai su `http://localhost/zumzeri`.

---

## Deployment in produzione

1. Carica tutti i file sul server **tranne** `/vendor/` e `.env`
2. Crea il `.env` direttamente sul server con le credenziali reali
3. Esegui `composer install` sul server
4. Importa il database su phpMyAdmin dell'hosting
5. Registra il sito su Google Search Console e carica `sitemap.xml`

---

## Note

- Questo progetto è in fase di sviluppo — alcuni contenuti (telefono, email, prezzi camere) sono ancora placeholder in attesa dei dati definitivi
- Le credenziali SMTP sono gestite tramite variabili d'ambiente (`.env`) e non sono mai nel codice
- Le password admin sono hashate con bcrypt (`password_hash` / `password_verify`)
- Il pannello admin è escluso dall'indicizzazione tramite `robots.txt`
- I prezzi delle camere sono placeholder — da aggiornare dal pannello admin

---

_Sviluppato da [Sabrina Motto](https://github.com/s-motto)_
