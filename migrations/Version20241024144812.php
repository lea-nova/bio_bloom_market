<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024144812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, ligne1 VARCHAR(255) NOT NULL, ligne2 VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(5) NOT NULL, ville VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, cedex VARCHAR(4) DEFAULT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(10) NOT NULL, mail VARCHAR(255) NOT NULL, service VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur_adresse (id INT AUTO_INCREMENT NOT NULL, id_fournisseur_id INT NOT NULL, id_adresse_id INT NOT NULL, INDEX IDX_119F47BB5A6AC879 (id_fournisseur_id), INDEX IDX_119F47BBE86D5C8B (id_adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(10) NOT NULL, date_naissance DATE NOT NULL, fidelite_client TINYINT(1) DEFAULT NULL, pref_achat VARCHAR(255) DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_adresse (user_id INT NOT NULL, adresse_id INT NOT NULL, INDEX IDX_9B52161CA76ED395 (user_id), INDEX IDX_9B52161C4DE7DC5C (adresse_id), PRIMARY KEY(user_id, adresse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fournisseur_adresse ADD CONSTRAINT FK_119F47BB5A6AC879 FOREIGN KEY (id_fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE fournisseur_adresse ADD CONSTRAINT FK_119F47BBE86D5C8B FOREIGN KEY (id_adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE user_adresse ADD CONSTRAINT FK_9B52161CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_adresse ADD CONSTRAINT FK_9B52161C4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur_adresse DROP FOREIGN KEY FK_119F47BB5A6AC879');
        $this->addSql('ALTER TABLE fournisseur_adresse DROP FOREIGN KEY FK_119F47BBE86D5C8B');
        $this->addSql('ALTER TABLE user_adresse DROP FOREIGN KEY FK_9B52161CA76ED395');
        $this->addSql('ALTER TABLE user_adresse DROP FOREIGN KEY FK_9B52161C4DE7DC5C');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE fournisseur_adresse');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_adresse');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
