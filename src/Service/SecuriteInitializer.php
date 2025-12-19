<?php

// src/Service/SecuriteInitializer.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Doctrine\ORM\EntityManagerInterface;
use Webgiciel2\InitBureauSecurite\Doctrine\SecurAdminChecker;

class SecuriteInitializer
{
    private bool $alreadyChecked = false;

    public function __construct(
        EntityManagerInterface $em,
        SecurAdminChecker $checker,
        SkeletonInstaller $installer,
        MigrationRunner $migrationRunner,
        DefaultUserCreator $defaultUserCreator,
        InitFlagManager $flagManager
    ) {}

    public function initializeIfNeeded(): void
    {
        if ($this->flagManager->isInitialized()) {
            return;
        }

        if (!$this->checker->entityExists()) {
            $this->createSecurity();
        } else {
            $this->checker->checkBaseUsers();
        }

        // flag posé À LA FIN
        $this->flagManager->markAsInitialized();
    }

    private function createSecurity(): void
    {
        // Étape A : créer entity + migration
        $this->installer->install();
        $this->migrationRunner->run();
        // Étape B : insert technicien / proprio
        $this->defaultUserCreator->createDefaults();
        // Étape C : envoyer email
    }


}
