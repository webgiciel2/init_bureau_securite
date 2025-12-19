<?php

// src/Service/DefaultUserCreator.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Webgiciel2\InitBureauSecurite\Entity\SecurAdmin

class DefaultUserCreator
{
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ActivationMailer $mailer
    ) {}

    public function createDefaults(): void
    {
        $this->createIfNotExists(
            email: 'tech@site.local',
            username: 'technicien',
            role: 'ROLE_TECHNICIEN'
        );

        $this->createIfNotExists(
            email: 'proprio@site.local',
            username: 'proprietaire',
            role: 'ROLE_PROPRIO'
        );
    }

    private function createIfNotExists(
        string $email,
        string $username,
        string $role
    ): void {
        $repo = $this->em->getRepository(SecurAdmin::class);

        if ($repo->findOneBy(['email' => $email])) {
            return;
        }

        $user = new SecurAdmin();
        $user->setEmail($email)
             ->setUsername($username)
             ->setRole($role)
             ->setActive(false)
             ->setCodeVerif(bin2hex(random_bytes(16)));

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'changeMe123!'
        );

        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->sendActivationMail($user);
    }
}
