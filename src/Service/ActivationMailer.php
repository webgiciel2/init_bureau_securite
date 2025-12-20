<?php

// src/Service/ActivationMailer.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Webgiciel2\InitBureauSecurite\Entity\SecurAdmin;

class ActivationMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private string $appUrl
    ) {}

    public function sendActivationMail(SecurAdmin $user): void
    {
        $activationLink = sprintf(
            '%s/activation?code=%s',
            rtrim($this->appUrl, '/'),
            $user->getCodeVerif()
        );

        $email = (new Email())
            ->from('stef.webgiciel@gmail.com')
            ->to($user->getEmail())
            ->subject('Activation de votre compte')
            ->html("
                <p>Bonjour {$user->getUsername()},</p>
                <p>Votre compte a été créé.</p>
                <p>
                    <a href='{$activationLink}'>
                        Cliquez ici pour activer votre compte
                    </a>
                </p>
            ");

        $this->mailer->send($email);
    }
}
