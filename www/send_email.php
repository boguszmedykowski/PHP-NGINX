<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    try {
        // Odbiór i oczyszczanie danych z formularza
        $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
        $email = isset($_POST['email']) ? strip_tags(trim($_POST['email'])) : '';
        $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';

        // Włącz debugowanie
        $mail->SMTPDebug = 2; // Ustawienie poziomu debugowania na 2

        // Konfiguracja PHPMailer do używania Gmail
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['SMTP_PORT'];

        // Adresaci
        $mail->setFrom($email, $name);
        $mail->addReplyTo($email, $name);
        $mail->addAddress('bogusz.medykowski@gmail.com', 'Bogusz');

        // Treść wiadomości
        $mail->isHTML(true);
        $mail->Subject = 'Nowa wiadomość od ' . $name;
        $mail->Body = 'Imię: ' . $name . '<br>Email: ' . $email . '<br>Wiadomość: ' . $message;
        $mail->AltBody = 'Imię: ' . $name . "\nEmail: " . $email . "\nWiadomość: " . $message;

        $mail->send();
        echo 'Wiadomość została wysłana';
    } catch (Exception $e) {
        echo "Wiadomość nie mogła zostać wysłana. Błąd PHPMailer: {$mail->ErrorInfo}";
    }
} else {
    echo 'Błąd: Żądanie musi być typu POST';
}
?>