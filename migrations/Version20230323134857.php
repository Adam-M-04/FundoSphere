<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323134857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donation (id INT AUTO_INCREMENT NOT NULL, donating_user_id INT DEFAULT NULL, fundraiser_id INT NOT NULL, amount INT NOT NULL, donation_date DATE NOT NULL, INDEX IDX_31E581A0CEA3F240 (donating_user_id), INDEX IDX_31E581A09F4D1DF6 (fundraiser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0CEA3F240 FOREIGN KEY (donating_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A09F4D1DF6 FOREIGN KEY (fundraiser_id) REFERENCES fundraising (id)');
        $this->addSql('ALTER TABLE fundraising CHANGE user_id fundraiser_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE fundraising ADD CONSTRAINT FK_39DF12F29AF71D75 FOREIGN KEY (fundraiser_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_39DF12F29AF71D75 ON fundraising (fundraiser_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0CEA3F240');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A09F4D1DF6');
        $this->addSql('DROP TABLE donation');
        $this->addSql('ALTER TABLE fundraising DROP FOREIGN KEY FK_39DF12F29AF71D75');
        $this->addSql('DROP INDEX IDX_39DF12F29AF71D75 ON fundraising');
        $this->addSql('ALTER TABLE fundraising CHANGE fundraiser_user_id user_id INT NOT NULL');
    }
}
