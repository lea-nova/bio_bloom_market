<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222095610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE fidelite_client fidelite_client TINYINT(1) NOT NULL');
        // $this->addSql('CREATE UNIQUE INDEX unique_default_address ON user_adresse (is_default)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE fidelite_client fidelite_client TINYINT(1) DEFAULT NULL');
        // $this->addSql('DROP INDEX unique_default_address ON user_adresse');
    }
}
