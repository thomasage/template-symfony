<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00000000000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof MySQL80Platform,
            \sprintf('Migration can only be executed safely on "%s".', MySQL80Platform::class),
        );

        if ($this->sm->tablesExist(['user'])) {
            $this->addSql('DELETE FROM user WHERE 1');

            return;
        }

        $this->addSql(
            <<<'SQL'
CREATE TABLE user ( id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                    uuid BINARY(16) NOT NULL COMMENT "(DC2Type:uuid)",
                    email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    roles JSON NOT NULL,
                    password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                    two_factors_authentication TINYINT(1) NOT NULL,
                    two_factors_authentication_email_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
                    last_login_at DATETIME DEFAULT NULL COMMENT "(DC2Type:datetime_immutable)",
                    UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
                    UNIQUE INDEX UNIQ_IDENTIFIER_UUID (uuid),
                    PRIMARY KEY(id) )
    DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
    ENGINE = InnoDB
SQL,
        );

        $this->addSql(
            <<<'SQL'
CREATE TABLE reset_password_request ( id INT AUTO_INCREMENT NOT NULL,
                                      user_id INT UNSIGNED NOT NULL,
                                      selector VARCHAR(20) NOT NULL,
                                      hashed_token VARCHAR(100) NOT NULL,
                                      requested_at DATETIME NOT NULL COMMENT "(DC2Type:datetime_immutable)",
                                      expires_at DATETIME NOT NULL COMMENT "(DC2Type:datetime_immutable)",
                                      INDEX IDX_7CE748AA76ED395 (user_id),
                                      PRIMARY KEY(id) )
    DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
    ENGINE = InnoDB
SQL,
        );
        $this->addSql(
            <<<'SQL'
ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
SQL,
        );
    }
}
