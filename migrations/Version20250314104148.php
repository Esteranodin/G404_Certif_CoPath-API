<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314104148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE img_scenario (id INT AUTO_INCREMENT NOT NULL, scenario_id INT DEFAULT NULL, img_path VARCHAR(255) DEFAULT NULL, img_alt VARCHAR(255) DEFAULT NULL, INDEX IDX_90D9CDD6E04E49DF (scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, scenario_id INT DEFAULT NULL, music_path VARCHAR(255) DEFAULT NULL, INDEX IDX_CD52224AE04E49DF (scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE img_scenario ADD CONSTRAINT FK_90D9CDD6E04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE img_scenario DROP FOREIGN KEY FK_90D9CDD6E04E49DF');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AE04E49DF');
        $this->addSql('DROP TABLE img_scenario');
        $this->addSql('DROP TABLE music');
    }
}
