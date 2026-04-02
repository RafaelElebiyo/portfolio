<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

class ContactService
{
    private array $mailConfig;

    public function __construct(array $mailConfig)
    {
        $this->mailConfig = $mailConfig;
    }

    /**
     * Send the contact form email.
     * Returns ['ok' => bool, 'error' => string|null]
     */
    public function send(array $data): array
    {
        $name    = $data['name'];
        $email   = $data['email'];
        $phone   = $data['phone'] ?? '';
        $subject = $data['subject'];
        $message = $data['message'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->mailConfig['user'];
            $mail->Password   = $this->mailConfig['pass'];
            $mail->SMTPSecure = $this->mailConfig['encryption'] === 'ssl'
                                ? PHPMailer::ENCRYPTION_SMTPS
                                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->mailConfig['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->mailConfig['from'], $this->mailConfig['from_name']);
            $mail->addAddress($this->mailConfig['to']);
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
            $mail->Body    = $this->buildHtmlBody($name, $email, $phone, $subject, $message);
            $mail->AltBody = $this->buildTextBody($name, $email, $phone, $subject, $message);

            $mail->send();
            return ['ok' => true, 'error' => null];
        } catch (MailerException $e) {
            error_log('ContactService mail error: ' . $mail->ErrorInfo);
            return ['ok' => false, 'error' => $mail->ErrorInfo];
        }
    }

    private function buildHtmlBody(
        string $name, string $email, string $phone,
        string $subject, string $message
    ): string {
        $safeName    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
        $safeEmail   = htmlspecialchars($email,   ENT_QUOTES, 'UTF-8');
        $safePhone   = htmlspecialchars($phone,   ENT_QUOTES, 'UTF-8');
        $safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $phoneRow    = $phone ? "<tr><td><strong>Phone:</strong></td><td>{$safePhone}</td></tr>" : '';

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head><meta charset="UTF-8"><title>{$safeSubject}</title>
        <style>
          body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;}
          .card{background:#fff;border-radius:8px;padding:30px;max-width:600px;margin:auto;
                box-shadow:0 2px 10px rgba(0,0,0,.1);}
          h2{color:#4a4a4a;border-bottom:2px solid #4a4a4a;padding-bottom:8px;}
          table{width:100%;border-collapse:collapse;margin-top:20px;}
          td{padding:8px 12px;vertical-align:top;}
          td:first-child{color:#666;white-space:nowrap;width:100px;}
          .message{background:#f9f9f9;border-left:4px solid #4a4a4a;padding:15px;
                   border-radius:0 4px 4px 0;margin-top:20px;}
          .footer{text-align:center;color:#999;font-size:12px;margin-top:20px;}
        </style>
        </head>
        <body>
          <div class="card">
            <h2>New Contact Message</h2>
            <table>
              <tr><td><strong>Name:</strong></td><td>{$safeName}</td></tr>
              <tr><td><strong>Email:</strong></td><td><a href="mailto:{$safeEmail}">{$safeEmail}</a></td></tr>
              {$phoneRow}
              <tr><td><strong>Subject:</strong></td><td>{$safeSubject}</td></tr>
            </table>
            <div class="message"><strong>Message:</strong><br><br>{$safeMessage}</div>
            <div class="footer">Sent via portfolio contact form</div>
          </div>
        </body></html>
        HTML;
    }

    private function buildTextBody(
        string $name, string $email, string $phone,
        string $subject, string $message
    ): string {
        $lines = ["Subject: {$subject}", "Name: {$name}", "Email: {$email}"];
        if ($phone) $lines[] = "Phone: {$phone}";
        $lines[] = "\nMessage:\n" . wordwrap($message, 70);
        return implode("\n", $lines);
    }
}
