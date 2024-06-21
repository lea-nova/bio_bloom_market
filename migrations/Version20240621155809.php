<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621155809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE telephone telephone VARCHAR(10) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE fidelite_client fidelite_client TINYINT(1) DEFAULT NULL, CHANGE pref_achat pref_achat VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE telephone telephone VARCHAR(10) NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE fidelite_client fidelite_client TINYINT(1) NOT NULL, CHANGE pref_achat pref_achat VARCHAR(255) NOT NULL');
    }
}
