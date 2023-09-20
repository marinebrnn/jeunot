<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230913093230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendee (uuid UUID NOT NULL, user_uuid UUID NOT NULL, event_uuid UUID NOT NULL, registered_on TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_1150D567ABFE1C6F ON attendee (user_uuid)');
        $this->addSql('CREATE INDEX IDX_1150D567CEB41C0D ON attendee (event_uuid)');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D567ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D567CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b11abfe1c6f');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b11ceb41c0d');
        $this->addSql('DROP TABLE participant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (uuid UUID NOT NULL, user_uuid UUID NOT NULL, event_uuid UUID NOT NULL, registered_on TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX idx_d79f6b11ceb41c0d ON participant (event_uuid)');
        $this->addSql('CREATE INDEX idx_d79f6b11abfe1c6f ON participant (user_uuid)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b11abfe1c6f FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b11ceb41c0d FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attendee DROP CONSTRAINT FK_1150D567ABFE1C6F');
        $this->addSql('ALTER TABLE attendee DROP CONSTRAINT FK_1150D567CEB41C0D');
        $this->addSql('DROP TABLE attendee');
    }
}
