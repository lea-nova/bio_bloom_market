<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014093748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fournisseur_adresse (id INT AUTO_INCREMENT NOT NULL, id_fournisseur_id INT NOT NULL, id_adresse_id INT NOT NULL, INDEX IDX_119F47BB5A6AC879 (id_fournisseur_id), INDEX IDX_119F47BBE86D5C8B (id_adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fournisseur_adresse ADD CONSTRAINT FK_119F47BB5A6AC879 FOREIGN KEY (id_fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE fournisseur_adresse ADD CONSTRAINT FK_119F47BBE86D5C8B FOREIGN KEY (id_adresse_id) REFERENCES adresse (id)');
        // $this->addSql('DROP INDEX UNIQ_C35F0816C288C859 ON adresse');
        // $this->addSql('ALTER TABLE user CHANGE uuid uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur_adresse DROP FOREIGN KEY FK_119F47BB5A6AC879');
        $this->addSql('ALTER TABLE fournisseur_adresse DROP FOREIGN KEY FK_119F47BBE86D5C8B');
        $this->addSql('DROP TABLE fournisseur_adresse');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_C35F0816C288C859 ON adresse (ulid)');
        // $this->addSql('ALTER TABLE user CHANGE uuid uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
