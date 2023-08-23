<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\DateUtilsInterface;
use App\Application\Mail\User\ConfirmAccountMailInterface;
use App\Application\User\Command\SendConfirmationLinkCommand;
use App\Application\User\Command\SendConfirmationLinkCommandHandler;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class SendConfirmationLinkCommandHandlerTest extends TestCase
{
    public function testSendConfirmationLink(): void
    {
        $now = new \DateTimeImmutable('2023-08-21');
        $user = $this->createMock(User::class);
        $user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn($user);

        $dateUtils = $this->createMock(DateUtilsInterface::class);
        $dateUtils
            ->expects(self::once())
            ->method('getNow')
            ->willReturn($now);

        $confirmAccountMail = $this->createMock(ConfirmAccountMailInterface::class);
        $confirmAccountMail
            ->expects(self::once())
            ->method('send')
            ->with('mathieu@fairness.coop', new \DateTimeImmutable('2023-08-31'));

        $handler = new SendConfirmationLinkCommandHandler(
            $userRepository,
            $confirmAccountMail,
            $dateUtils,
        );

        ($handler)(new SendConfirmationLinkCommand('mathieu@fairness.coop'));
    }

    public function testUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn(null);

        $dateUtils = $this->createMock(DateUtilsInterface::class);
        $dateUtils
            ->expects(self::never())
            ->method('getNow');

        $confirmAccountMail = $this->createMock(ConfirmAccountMailInterface::class);
        $confirmAccountMail
            ->expects(self::never())
            ->method('send');

        $handler = new SendConfirmationLinkCommandHandler(
            $userRepository,
            $confirmAccountMail,
            $dateUtils,
        );

        ($handler)(new SendConfirmationLinkCommand('mathieu@fairness.coop'));
    }
}
