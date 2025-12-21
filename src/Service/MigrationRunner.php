<?php

// src/Service/MigrationRunner.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Symfony\Component\Process\Process;
use Symfony\Component\HttpKernel\KernelInterface;

class MigrationRunner
{
    public function __construct(KernelInterface $kernel)
    {
        $this->projectDir = $kernel->getProjectDir();
    }

    public function run(): void
    {
        $process = new Process([
            'php',
            'bin/console',
            'doctrine:migrations:migrate',
            '--no-interaction'
        ], $this->projectDir);

        $process->run();
    }
}
