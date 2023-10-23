<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231023113050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT FK_12467250CEB41C0D');
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT FK_124672503F70EF10');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT FK_12467250CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT FK_124672503F70EF10 FOREIGN KEY (tag_uuid) REFERENCES tag (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT fk_12467250ceb41c0d');
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT fk_124672503f70ef10');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT fk_12467250ceb41c0d FOREIGN KEY (event_uuid) REFERENCES event (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT fk_124672503f70ef10 FOREIGN KEY (tag_uuid) REFERENCES tag (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
