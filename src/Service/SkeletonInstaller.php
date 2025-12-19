<?php

// src/Service/SkeletonInstaller.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Symfony\Component\Filesystem\Filesystem;

class SkeletonInstaller
{
    public function __construct(
        private string $projectDir
    ) {}

    public function install(): void
    {
        $fs = new Filesystem();

        // ENTITY
        $fs->copy(
            __DIR__.'/../Resources/skeleton/Entity/Securite/SecurAdmin.php',
            $this->projectDir.'/src/Entity/Securite/SecurAdmin.php'
        );

        // MIGRATION
        $migrationName = 'Version'.date('YmdHis').'.php';

        $fs->copy(
            __DIR__.'/../Resources/skeleton/migrations/Version00000000000000.php',
            $this->projectDir.'/migrations/'.$migrationName
        );
    }
}
