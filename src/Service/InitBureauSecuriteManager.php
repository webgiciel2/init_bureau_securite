<?php

namespace Webgiciel2\InitBureauSecurite\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Entity\Securite\SecurAdmin;

class InitBureauSecuriteManager
{
    private string $lockFile;
    private array $proprietaire;
    private array $technicien;

    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $params,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
        KernelInterface $kernel
    ) {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;

        $this->proprietaire = $params->get('init_bureau_securite.proprietaire');
        $this->technicien = $params->get('init_bureau_securite.technicien');

        $this->lockFile = $kernel->getProjectDir() . '/var/init_bureau_securite.lock';
    }

    /**
     * Méthode principale appelée au premier accès web
     */
    public function initialize(): void
    {
        // 🔒 Si déjà initialisé → on sort
        if (file_exists($this->lockFile)) {
            return;
        }

        // 1️⃣ Vérifier si l’entity existe déjà en base
        $repo = $this->em->getRepository(SecurAdmin::class);

        $proprio = $repo->findOneBy(['role' => 'ROLE_PROPRIO']);
        $tech    = $repo->findOneBy(['role' => 'ROLE_TECHNICIEN']);

        if (!$proprio) {
            $proprio = $this->createUser(
                $this->proprietaire['username'],
                $this->proprietaire['email'],
                'ROLE_PROPRIO'
            );
        }

        if (!$tech) {
            $tech = $this->createUser(
                $this->technicien['username'],
                $this->technicien['email'],
                'ROLE_TECHNICIEN'
            );
        }

        $this->em->flush();

        // 2️⃣ Envoi des emails d’activation
        $this->sendActivationEmail($proprio);
        $this->sendActivationEmail($tech);

        // 3️⃣ Création du fichier lock
        file_put_contents(
            $this->lockFile,
            'initialized at ' . date('Y-m-d H:i:s')
        );
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
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            bin2hex(random_bytes(8))
        );

        $user->setPassword($hashedPassword);

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
