<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025173345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE placement (id INT AUTO_INCREMENT NOT NULL, race_id INT DEFAULT NULL, full_name VARCHAR(255) NOT NULL, finish_time VARCHAR(8) NOT NULL, over_all_place INT NOT NULL, age_cat_place INT NOT NULL, age_category VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_48DB750E6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE racing_data (id INT AUTO_INCREMENT NOT NULL, race_id INT DEFAULT NULL, full_name VARCHAR(255) NOT NULL, race_distance VARCHAR(10) NOT NULL, race_time VARCHAR(8) NOT NULL, age_category VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_A12A4F5E6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750E6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE racing_data ADD CONSTRAINT FK_A12A4F5E6E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750E6E59D40D');
        $this->addSql('ALTER TABLE racing_data DROP FOREIGN KEY FK_A12A4F5E6E59D40D');
        $this->addSql('DROP TABLE placement');
        $this->addSql('DROP TABLE racing_data');
    }
}
