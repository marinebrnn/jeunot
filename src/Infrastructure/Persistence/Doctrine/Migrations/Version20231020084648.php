<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020084648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD registration_date TIMESTAMP(0) WITH TIME ZONE NOT NULL DEFAULT now()');
        $this->addSql('ALTER TABLE "user" ADD gender VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD display_my_age BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD biography VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP registration_date');
        $this->addSql('ALTER TABLE "user" DROP gender');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" DROP display_my_age');
        $this->addSql('ALTER TABLE "user" DROP biography');
    }
}
