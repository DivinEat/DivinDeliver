<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailService
{
    private $mailer;
    private $translator;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
     * Send a reset password mail
     */
    public function sendResetPasswordMail(String $to, String $token, int $userid)
    {
        $subject = $this->translator->trans('password.reset_pwd_title');
        $from = 'contact@divindeliver.fr';
        $htmlTemplate = 'emails/password/reset_password.html.twig';
        $context = ['token' => $token, 'userid' => $userid];

        $this->sendMail($from, $to, $subject, $htmlTemplate, $context);
    }

    /**
     * Send a create password mail to new user
     */
    public function sendCreatePasswordNewUserMail(String $to, String $token, int $userid)
    {
        $subject = $this->translator->trans('password.set_pwd_title');
        $from = 'contact@divindeliver.fr';
        $htmlTemplate = 'emails/password/new_user_password.html.twig';
        $context = ['token' => $token, 'userid' => $userid];

        $this->sendMail($from, $to, $subject, $htmlTemplate, $context);
    }

    /**
     * Send an account validation mail to new user
     */
    public function sendAccountValidationMail(String $to, String $token, int $userid)
    {
        $subject = $this->translator->trans('account.validation.mail.title');
        $from = 'contact@divindeliver.fr'; // todo : à configurer dans le restau
        $htmlTemplate = 'emails/account/validation.html.twig';
        $context = ['token' => $token, 'userid' => $userid];

        $this->sendMail($from, $to, $subject, $htmlTemplate, $context);
    }

    /**
     * Send a mail
     */
    public function sendMail(
        String $from,
        String $to,
        String $subject,
        String $htmlTemplate = null,
        array $context = null,
        String $text = null
    ) {
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
