<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250609140707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ADD: average_rating and ratings_count to scenario table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE scenario ADD average_rating NUMERIC(3, 1) DEFAULT \'0\' NOT NULL, ADD ratings_count INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE scenario DROP average_rating, DROP ratings_count');
    }
}
