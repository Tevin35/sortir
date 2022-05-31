<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531074407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip ADD state_id INT NOT NULL, DROP state');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_7656F53B5D83CC1 ON trip (state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B5D83CC1');
        $this->addSql('DROP INDEX IDX_7656F53B5D83CC1 ON trip');
        $this->addSql('ALTER TABLE trip ADD state VARCHAR(50) NOT NULL, DROP state_id');
    }
}
