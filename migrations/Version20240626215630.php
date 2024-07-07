<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626215630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_adresse (user_id INT NOT NULL, adresse_id INT NOT NULL, INDEX IDX_9B52161CA76ED395 (user_id), INDEX IDX_9B52161C4DE7DC5C (adresse_id), PRIMARY KEY(user_id, adresse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_adresse ADD CONSTRAINT FK_9B52161CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_adresse ADD CONSTRAINT FK_9B52161C4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_adresse DROP FOREIGN KEY FK_9B52161CA76ED395');
        $this->addSql('ALTER TABLE user_adresse DROP FOREIGN KEY FK_9B52161C4DE7DC5C');
        $this->addSql('DROP TABLE user_adresse');
    }
}
