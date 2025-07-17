<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250607172845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial database schema for the application';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE campaign (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            theme VARCHAR(255) DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_1F1512DDA76ED395 (user_id),
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, scenario_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_68C58ED9E04E49DF (scenario_id), INDEX IDX_68C58ED9A76ED395 (user_id), UNIQUE INDEX UNIQ_FAVORITE_USER_SCENARIO (user_id, scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE img_scenario (id INT AUTO_INCREMENT NOT NULL, scenario_id INT DEFAULT NULL, user_id INT NOT NULL, img_path VARCHAR(255) DEFAULT NULL, img_alt VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_90D9CDD6E04E49DF (scenario_id), INDEX IDX_90D9CDD6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, scenario_id INT DEFAULT NULL, user_id INT NOT NULL, music_path VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD52224AE04E49DF (scenario_id), INDEX IDX_CD52224AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, scenario_id INT NOT NULL, user_id INT NOT NULL, score SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D8892622E04E49DF (scenario_id), INDEX IDX_D8892622A76ED395 (user_id), UNIQUE INDEX UNIQ_RATING_USER_SCENARIO (user_id, scenario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scenario (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3E45C8D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scenario_campaign (scenario_id INT NOT NULL, campaign_id INT NOT NULL, INDEX IDX_1F0AD41DE04E49DF (scenario_id), INDEX IDX_1F0AD41DF639F774 (campaign_id), PRIMARY KEY(scenario_id, campaign_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(17) DEFAULT NULL, is_ban TINYINT(1) DEFAULT 0, avatar VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campaign ADD CONSTRAINT FK_1F1512DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9E04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE img_scenario ADD CONSTRAINT FK_90D9CDD6E04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE img_scenario ADD CONSTRAINT FK_90D9CDD6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622E04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scenario ADD CONSTRAINT FK_3E45C8D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scenario_campaign ADD CONSTRAINT FK_1F0AD41DE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scenario_campaign ADD CONSTRAINT FK_1F0AD41DF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE campaign DROP FOREIGN KEY FK_1F1512DDA76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9E04E49DF');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE img_scenario DROP FOREIGN KEY FK_90D9CDD6E04E49DF');
        $this->addSql('ALTER TABLE img_scenario DROP FOREIGN KEY FK_90D9CDD6A76ED395');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AE04E49DF');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AA76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622E04E49DF');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE scenario DROP FOREIGN KEY FK_3E45C8D8A76ED395');
        $this->addSql('ALTER TABLE scenario_campaign DROP FOREIGN KEY FK_1F0AD41DE04E49DF');
        $this->addSql('ALTER TABLE scenario_campaign DROP FOREIGN KEY FK_1F0AD41DF639F774');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE img_scenario');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('DROP TABLE scenario_campaign');
        $this->addSql('DROP TABLE user');
    }
}
