<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-5.2.28/src/Exception.php';
require 'PHPMailer-5.2.28/src/PHPMailer.php';
require 'PHPMailer-5.2.28/src/SMTP.php';

$mail = new PHPMailer(true);

// Zieladresse (also deine GMX-Adresse)
$mail_to_email = 'myzu@gmx.net';
$mail_to_name = 'Myzu Tattoo Booking';

// Login-Daten für GMX SMTP
$gmx_email = 'myzu@gmx.net';
$gmx_passwort = ''; // Ggf. App-Passwort, falls 2FA aktiv

try {
    // Formulardaten auslesen
    $mail_from_name = $_POST['name'] ?? '';
    $mail_from_email = $_POST['email'] ?? '';
    $mail_category = $_POST['category'] ?? '';
    $mail_budget = $_POST['budget'] ?? '';
    $mail_message = $_POST['message'] ?? '';

    // SMTP Settings
    $mail->isSMTP();
    $mail->Host = 'mail.gmx.net';
    $mail->SMTPAuth = true;
    $mail->Username = $gmx_email;
    $mail->Password = $gmx_passwort;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Absender & Empfänger
    $mail->setFrom($gmx_email, 'Tattoo Anfrage über Website');
    $mail->addAddress($mail_to_email, $mail_to_name);

    // E-Mail Inhalt
    $mail->isHTML(true);
    $mail->Subject = 'Neue Tattoo-Anfrage über das Kontaktformular';
    $mail->Body = "
        <strong>Name:</strong> {$mail_from_name}<br>
        <strong>Email:</strong> {$mail_from_email}<br>
        <strong>Category:</strong> {$mail_category}<br>
        <strong>Budget:</strong> {$mail_budget}<br>
        <strong>Message:</strong><br>
        <pre>{$mail_message}</pre>
    ";

    $mail->send();

    // Erfolgsmeldung (z. B. Pop-up + Weiterleitung)
    echo '<script>alert("Deine Anfrage wurde erfolgreich gesendet!"); window.location.href="../index.html#Contact";</script>';

} catch (Exception $e) {
    // Fehlermeldung
    echo '<script>alert("Fehler beim Versenden: ' . $mail->ErrorInfo . '"); history.back();</script>';
}
