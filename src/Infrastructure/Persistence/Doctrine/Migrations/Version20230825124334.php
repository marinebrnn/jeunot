<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825124334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add event models';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (uuid UUID NOT NULL, user_uuid UUID NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, start_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, published BOOLEAN NOT NULL, picture VARCHAR(255) DEFAULT NULL, location VARCHAR(255) NOT NULL, initial_available_places INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7ABFE1C6F ON event (user_uuid)');
        $this->addSql('CREATE TABLE event_tag (event_uuid UUID NOT NULL, tag_uuid UUID NOT NULL, PRIMARY KEY(event_uuid, tag_uuid))');
        $this->addSql('CREATE INDEX IDX_12467250CEB41C0D ON event_tag (event_uuid)');
        $this->addSql('CREATE INDEX IDX_124672503F70EF10 ON event_tag (tag_uuid)');
        $this->addSql('CREATE TABLE participant (uuid UUID NOT NULL, user_uuid UUID NOT NULL, event_uuid UUID NOT NULL, registered_on TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_D79F6B11ABFE1C6F ON participant (user_uuid)');
        $this->addSql('CREATE INDEX IDX_D79F6B11CEB41C0D ON participant (event_uuid)');
        $this->addSql('CREATE TABLE tag (uuid UUID NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX tag_title ON tag (title)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT FK_12467250CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_tag ADD CONSTRAINT FK_124672503F70EF10 FOREIGN KEY (tag_uuid) REFERENCES tag (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7ABFE1C6F');
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT FK_12467250CEB41C0D');
        $this->addSql('ALTER TABLE event_tag DROP CONSTRAINT FK_124672503F70EF10');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11ABFE1C6F');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11CEB41C0D');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_tag');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE tag');
    }
}
