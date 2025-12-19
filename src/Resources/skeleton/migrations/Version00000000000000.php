<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00000000000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create secur_admin table with authentication fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE secur_admin (
                id INT AUTO_INCREMENT NOT NULL,
                email VARCHAR(180) NOT NULL,
                username VARCHAR(180) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) NOT NULL,
                active TINYINT(1) NOT NULL,
                code_verif VARCHAR(255) DEFAULT NULL,
                date_crea DATETIME NOT NULL,
                date_reset_password DATETIME DEFAULT NULL,
                UNIQUE INDEX UNIQ_SECUR_ADMIN_EMAIL (email),
                UNIQUE INDEX UNIQ_SECUR_ADMIN_USERNAME (username),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE secur_admin');
    }
}
