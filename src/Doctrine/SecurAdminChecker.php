<?php

// src/Doctrine/SecurAdminChecker.php
namespace Webgiciel2\InitBureauSecurite\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

class SecurAdminChecker
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function entityExists(): bool
    {
        return class_exists('App\\Entity\\Securite\\SecurAdmin');
    }

    public function checkBaseUsers(): void
    {
        // vérifier technicien / proprio
    }
}
