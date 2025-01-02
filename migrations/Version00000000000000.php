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
    }
}
