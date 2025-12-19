<?php

// src/Service/MigrationRunner.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Symfony\Component\Process\Process;

class MigrationRunner
{
    public function __construct(
        private string $projectDir
    ) {}

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
