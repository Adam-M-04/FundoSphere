<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417163305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_fundraisers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, favorite_fundraiser_id_id INT NOT NULL, INDEX IDX_F7A27F59D86650F (user_id_id), INDEX IDX_F7A27F56CE5E2F9 (favorite_fundraiser_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_fundraisers ADD CONSTRAINT FK_F7A27F59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_fundraisers ADD CONSTRAINT FK_F7A27F56CE5E2F9 FOREIGN KEY (favorite_fundraiser_id_id) REFERENCES fundraising (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_fundraisers DROP FOREIGN KEY FK_F7A27F59D86650F');
        $this->addSql('ALTER TABLE favorite_fundraisers DROP FOREIGN KEY FK_F7A27F56CE5E2F9');
        $this->addSql('DROP TABLE favorite_fundraisers');
    }
}
