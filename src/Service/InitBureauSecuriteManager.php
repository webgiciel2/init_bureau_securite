<?php

namespace Webgiciel2\InitBureauSecurite\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpKernel\KernelInterface;

use Webgiciel2\InitBureauSecurite\Entity\SecurAdmin;

class InitBureauSecuriteManager
{
    private string $lockFile;

    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private ActivationMailer $mailer,
        private InitFlagManager $flagManager,
        KernelInterface $kernel,

        private string $proprioUsername,
        private string $proprioEmail,
        private string $techUsername,
        private string $techEmail
    ) {
    }

    /**
     * Méthode principale appelée au premier accès web
     */
    public function initialize(): void
    {
        // Si déjà initialisé → on sort
        if ($this->flagManager->isInitialized()) {
            return;
        }

        try {
            // Vérifier si l’entity existe déjà en base
            $repo = $this->em->getRepository(SecurAdmin::class);

            $this->createIfNotExists(
                $repo,
                $this->proprioUsername,
                $this->proprioEmail,
                'ROLE_PROPRIO'
            );

            $this->createIfNotExists(
                $repo,
                $this->techUsername,
                $this->techEmail,
                'ROLE_TECHNICIEN'
            );

            $this->em->flush();
            $this->flagManager->markAsInitialized();
        } catch (\Throwable $e) {
            // Optionnel : log
            throw $e;
        }
    }

    private function createIfNotExists(
        $repo,
        string $username,
        string $email,
        string $role
    ): void {
        if ($repo->findOneBy(['email' => $email])) {
            return;
        }

        $user = new SecurAdmin();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setDateCrea(new \DateTime());
        $user->setCodeVerif(bin2hex(random_bytes(16)));

        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                bin2hex(random_bytes(8))
            )
        );

        $this->em->persist($user);
        $this->mailer->sendActivationMail($user);
    }

    private function createUser(string $username, string $email, string $role): SecurAdmin
    {
        $user = new SecurAdmin();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles([$role]);
        $user->setDateCrea(new \DateTime());
        $user->setCodeVerif(bin2hex(random_bytes(16)));

        // Mot de passe temporaire
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                bin2hex(random_bytes(8))
            )
        );

        $this->em->persist($user);

        return $user;
    }

    private function sendActivationEmail(SecurAdmin $user): void
    {
        $email = (new Email())
            ->from('no-reply@site.local')
            ->to($user->getEmail())
            ->subject('Activation de votre compte')
            ->text(
                sprintf(
                    "Bonjour %s,\n\nCode d’activation : %s",
                    $user->getUsername(),
                    $user->getCodeVerif()
                )
            );

        $this->mailer->send($email);
    }
}
