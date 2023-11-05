<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105084842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create admin account';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        INSERT INTO "user" (uuid, first_name, last_name, email, password, role, birthday, registration_date, is_verified) VALUES
            (\'a7ec3606-c9cf-4319-8001-61e804ecfcfc\', \'Marine\', \'Bironneau\', \'marinebrnn@gmail.com\', \'$2y$13$KasZ3UOxXgkz4xBzm4ZGwO5NKMihXY0x/JlcOh80R6L5RWv7iz3eC\', \'ROLE_ADMIN\', \'1900-01-01\', \'2023-11-05\', true),
            (\'54ea4d71-4ae5-4e86-b8af-312ccca95b6b\', \'Julien\', \'Vericel\', \'julien.vericel@free.fr\', \'$2y$13$Mm/zIqE1q2W7AyDTOCYA4uBlU.osYM1baX.YbxZcWw8K9BvsTk.su\', \'ROLE_ADMIN\', \'1900-01-01\', \'2023-11-05\', true)
    ');
    }

    public function down(Schema $schema): void
    {
    }
}
