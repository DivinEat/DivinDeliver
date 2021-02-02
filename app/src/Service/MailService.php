<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send a reset password mail
     */
    public function sendResetPasswordMail(String $to, String $token, int $userid)
    {
        $subject = "Reset your password";
        $from = 'noreply@divindeliver.com';
        $htmlTemplate = 'emails/password/reset_password.html.twig';
        $context = ['token' => $token, 'userid' => $userid];
    
        $this->sendMail($from, $to, $subject, $htmlTemplate, $context);
    }
    
    /**
     * Send a mail
     */
    public function sendMail(String $from, String $to, String $subject,
                             String $htmlTemplate = null, array $context = null,
                             String $text = null)
    {
        $email = empty($htmlTemplate) ? (new Email()) : (new TemplatedEmail());

        $email->from($from)
            ->to($to)
            ->subject($subject);

        if (!empty($htmlTemplate))
            $email->htmlTemplate($htmlTemplate);

        if (!empty($htmlTemplate) && !empty($context))
            $email->context($context);

        if (!empty($text))
            $email->text($text);

        $this->mailer->send($email);
    }
}
