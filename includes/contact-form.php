<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    
    $defaultSubject = "Nuevo mensaje de contacto";
    $emailSubject = !empty($subject) ? $subject : "$defaultSubject de $name";
    $toEmail = "rafaelelebiyomedina1@gmail.com"; 
    $fromEmail = "no-reply@rafael-elebiyo-medina.com"; 
    $fromName = "Rafael Elebiyo Medina Contact Form";

    // Validaciones
    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../contact.php?status=error');
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.rafael-elebiyo-medina.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@rafael-elebiyo-medina.com'; 
        $mail->Password = 'rafelemed01!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $emailSubject;
        
        $mail->Body = "
            <html>
            <head>
                <title>$emailSubject</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    h2 { color: #333; }
                    p { margin: 5px 0; }
                    strong { color: #555; }
                </style>
            </head>
            <body>
                <h2>$emailSubject</h2>
                <p><strong>Nombre:</strong> $name</p>
                <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
                ".(!empty($phone) ? "<p><strong>Teléfono:</strong> $phone</p>" : "")."
                <p><strong>Mensaje:</strong></p>
                <p>".nl2br($message)."</p>
                <hr>
                <p><small>Este mensaje fue enviado desde el formulario de contacto de $fromName</small></p>
            </body>
            </html>
        ";
        
        $mail->AltBody = "$emailSubject\n\n" .
                         "Nombre: $name\n" .
                         "Email: $email\n" .
                         (!empty($phone) ? "Teléfono: $phone\n" : "") .
                         "\nMensaje:\n" . wordwrap($message, 70) . "\n\n" .
                         "-- \nEste mensaje fue enviado desde el formulario de contacto de $fromName";

        $mail->send();
        header('Location: ../contact.php?status=success');
    } catch (Exception $e) {

        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        header('Location: ../contact.php?status=error');
    }
    exit;
} else {
    header('Location: ../contact.php?status=error');
    exit;
}
?>