<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223190632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add TOTP authentication';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<'SQL'
ALTER TABLE user
    ADD two_factors_authentication_totp_enabled TINYINT(1) NOT NULL,
    ADD two_factors_authentication_totp_secret VARCHAR(255) DEFAULT NULL,
    CHANGE uuid uuid BINARY(16) NOT NULL,
    CHANGE last_login_at last_login_at DATETIME DEFAULT NULL,
    CHANGE two_factors_authentication two_factors_authentication_email_enabled TINYINT(1) NOT NULL
SQL,
        );
    }
}
