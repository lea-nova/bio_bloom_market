<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311232347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unites_mesures (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, symbole VARCHAR(20) NOT NULL, facteur_conversion DOUBLE PRECISION NOT NULL, categorie_mesure VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit ADD unite_mesure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C75A06BF FOREIGN KEY (unite_mesure_id) REFERENCES unites_mesures (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27C75A06BF ON produit (unite_mesure_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C75A06BF');
        $this->addSql('DROP TABLE unites_mesures');
        $this->addSql('DROP INDEX IDX_29A5EC27C75A06BF ON produit');
        $this->addSql('ALTER TABLE produit DROP unite_mesure_id');
    }
}
