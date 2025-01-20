<?php

require 'vendor/autoload.php';

//using .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['solicitudEnviada'])) {
  $nombres = $_POST['nombresSolicitante'];
  $email = $_POST['correoSolicitante'];
  $pais = $_POST['paisSolicitante'];
  $telefono = $_POST['telefonoSolicitante'];
  $paquete = $_POST['paqueteSolicitante'];
  $niñosNum = $_POST['niños'];
  $adultosNum = $_POST['adultos'];
  $fechas = $_POST['fechas'];
  $edadesNiños = $_POST['edades-niños'];


  $mail = new PHPMailer(true);

  if (!PHPMailer::validateAddress($email)) {
    echo "<script type='text/javascript'>alert('Correo Invalid');</script>";
    header('Location: result.php');
    exit;
  }

  try {
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Username = $_ENV['SMTP_EMAIL']; // Email
    $mail->Password = $_ENV['SMTP_PASSWORD']; // App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //mail to the hotel
    $mail->setFrom($_ENV['SMTP_EMAIL']); // Sender email
    $mail->addAddress($_ENV['SMTP_EMAIL']); // Recipient email
    $mail->isHTML(true);
    $mail->Subject = 'Nueva Solicitud de Paquete';
    $mail->Body = 'Solcitud de habitaciones para el paquete: ' . $paquete .
      '<br>Para los dias: ' . $fechas .
      '<br>Nombres: ' . $nombres .
      '<br>Correo: ' . $email .
      '<br>Pais: ' . $pais .
      '<br>Telefono: ' . $telefono .
      '<br>Numero de niños: ' . $niñosNum .
      '<br>Edades de los niños: ' . $edadesNiños .
      '<br>Numero de adultos: ' . $adultosNum;

    $mail->send();

    $mail->clearAddresses();
    $mail->clearAttachments();

    //mail to the client
    $mail->setFrom($_ENV['SMTP_EMAIL']); // Sender email
    $mail->addAddress($email); // Recipient email
    $mail->isHTML(true);
    $mail->Subject = 'Confirmacion de Solicitud';
    $mail->Body = 'Solcitud de habitaciones para el paquete: ' . $paquete .
      '<br>Su solicitud ha sido enviada con exito. Nos pondremos en contacto con usted lo mas pronto posible.';
    $mail->send();
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }

  header('Location: solicitud_enviada.php');

  exit;

}