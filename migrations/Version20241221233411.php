<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221233411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_default_address ON user_adresse');
        $this->addSql('CREATE UNIQUE INDEX unique_default_address ON user_adresse (is_default)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_default_address ON user_adresse');
        $this->addSql('CREATE UNIQUE INDEX unique_default_address ON user_adresse (user_id, is_default)');
    }
}
