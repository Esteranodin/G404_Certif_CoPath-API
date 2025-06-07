<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250607154601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify created_at column in favorite table to be non-nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE favorite CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE favorite CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
