<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\StorageInterface;
use App\Application\User\Command\UpdateProfileCommand;
use App\Application\User\Command\UpdateProfileCommandHandler;
use App\Domain\User\Enum\GenderEnum;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdateProfileCommandHandlerTest extends TestCase
{
    private MockObject $isUserAlreadyRegistered;
    private MockObject $storage;
    private MockObject $user;
    private MockObject $file;
    private UpdateProfileCommand $command;

    public function setUp(): void
    {
        $this->storage = $this->createMock(StorageInterface::class);
        $this->isUserAlreadyRegistered = $this->createMock(IsUserAlreadyRegistered::class);
        $this->file = $this->createMock(UploadedFile::class);
        $this->user = $this->createMock(User::class);
        $this->user
            ->expects(self::once())
            ->method('getBirthday')
            ->willReturn(new \DateTime('1989-09-17'));

        $command = new UpdateProfileCommand($this->user);
        $command->firstName = 'Mathieu';
        $command->lastName = 'MARCHOIS';
        $command->email = '  matHieu@fAirness.coop   ';
        $command->biography = 'Je suis développeur';
        $command->gender = GenderEnum::MALE->value;
        $command->city = 'Paris';
        $command->displayMyAge = true;
        $command->birthday = new \DateTime('1989-09-17');
        $command->file = $this->file;

        $this->command = $command;
    }

    public function testProfileUpdated(): void
    {
        $birthday = new \DateTime('1989-09-17');

        $this->user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');
        $this->user
            ->expects(self::exactly(2))
            ->method('getAvatar')
            ->willReturn('picture.jpeg');
        $this->user
            ->expects(self::once())
            ->method('getUuid')
            ->willReturn('8a8a01ab-9d65-48d8-af16-a9639f885c65');
        $this->user
            ->expects(self::once())
            ->method('updateAvatar')
            ->with('8a8a01ab-9d65-48d8-af16-a9639f885c65.jpeg');

        $this->storage
            ->expects(self::once())
            ->method('delete')
            ->with('picture.jpeg');

        $this->storage
            ->expects(self::once())
            ->method('write')
            ->with('8a8a01ab-9d65-48d8-af16-a9639f885c65', $this->file)
            ->willReturn('8a8a01ab-9d65-48d8-af16-a9639f885c65.jpeg');

        $this->user
             ->expects(self::once())
             ->method('updateProfile')
             ->with(
                 'Mathieu',
                 'MARCHOIS',
                 'mathieu@fairness.coop',
                 'Je suis développeur',
                 GenderEnum::MALE->value,
                 'Paris',
                 true,
                 $birthday,
             );

        $this->isUserAlreadyRegistered
            ->expects(self::never())
            ->method('isSatisfiedBy');

        $handler = new UpdateProfileCommandHandler($this->isUserAlreadyRegistered, $this->storage);
        $this->assertEquals(($handler)($this->command), $this->user);
    }

    public function testProfileUpdatedWithoutFile(): void
    {
        $this->command->file = null;
        $birthday = new \DateTime('1989-09-17');

        $this->user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');
        $this->user
            ->expects(self::never())
            ->method('getAvatar');
        $this->user
            ->expects(self::never())
            ->method('getUuid');
        $this->user
            ->expects(self::never())
            ->method('updateAvatar');
        $this->storage
            ->expects(self::never())
            ->method('delete');
        $this->storage
            ->expects(self::never())
            ->method('write');

        $this->user
             ->expects(self::once())
             ->method('updateProfile')
             ->with(
                 'Mathieu',
                 'MARCHOIS',
                 'mathieu@fairness.coop',
                 'Je suis développeur',
                 GenderEnum::MALE->value,
                 'Paris',
                 true,
                 $birthday,
             );

        $this->isUserAlreadyRegistered
            ->expects(self::never())
            ->method('isSatisfiedBy');

        $handler = new UpdateProfileCommandHandler($this->isUserAlreadyRegistered, $this->storage);
        $this->assertEquals(($handler)($this->command), $this->user);
    }

    public function testUserAlreadyExisted(): void
    {
        $this->expectException(UserAlreadyRegisteredException::class);
        $this->command->email = 'helene@fairness.coop';

        $this->user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');

        $this->user
            ->expects(self::never())
            ->method('updateProfile');
        $this->storage
            ->expects(self::never())
            ->method('write');
        $this->storage
            ->expects(self::never())
            ->method('delete');

        $this->isUserAlreadyRegistered
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with('helene@fairness.coop')
            ->willReturn(true);

        $handler = new UpdateProfileCommandHandler($this->isUserAlreadyRegistered, $this->storage);
        ($handler)($this->command);
    }
}
