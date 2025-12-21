<?php

// src/Service/InitFlagManager.php
namespace Webgiciel2\InitBureauSecurite\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class InitFlagManager
{
    private string $lockFile;

    public function __construct(KernelInterface $kernel)
    {
        $this->lockFile = $kernel->getProjectDir() . '/var/init_bureau_securite.lock';
    }

    public function isInitialized(): bool
    {
        return file_exists($this->lockFile);
    }

    public function markAsInitialized(): void
    {
        file_put_contents(
            $this->lockFile,
            'initialized at ' . date('Y-m-d H:i:s')
        );
    }

    public function reset(): void
    {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }
}
