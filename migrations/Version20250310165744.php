<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250310165744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campaign (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, theme VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scenario_campaign (scenario_id INT NOT NULL, campaign_id INT NOT NULL, INDEX IDX_1F0AD41DE04E49DF (scenario_id), INDEX IDX_1F0AD41DF639F774 (campaign_id), PRIMARY KEY(scenario_id, campaign_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scenario_campaign ADD CONSTRAINT FK_1F0AD41DE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scenario_campaign ADD CONSTRAINT FK_1F0AD41DF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scenario_campaign DROP FOREIGN KEY FK_1F0AD41DE04E49DF');
        $this->addSql('ALTER TABLE scenario_campaign DROP FOREIGN KEY FK_1F0AD41DF639F774');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP TABLE scenario_campaign');
    }
}
