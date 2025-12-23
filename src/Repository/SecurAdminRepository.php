<?php

namespace Webgiciel2\InitBureauSecurite\Repository;

use Webgiciel2\InitBureauSecurite\Entity\SecurAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SecurAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecurAdmin::class);
    }

    public function findOneByCode(string $code): ?SecurAdmin
    {
        return $this->findOneBy([
            'codeVerif' => $code,
        ]);
    }
}
