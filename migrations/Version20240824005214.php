<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240824005214 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<'SQL'
CREATE TABLE user ( id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                    email VARCHAR(180) NOT NULL,
                    roles JSON NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
                    PRIMARY KEY(id) ) DEFAULT CHARACTER SET utf8mb4
SQL
        );
    }
}
