<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Query;

use App\Application\DateUtilsInterface;
use App\Application\User\Query\GetUserProfileQuery;
use App\Application\User\Query\GetUserProfileQueryHandler;
use App\Application\User\View\ProfileView;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetUserProfileQueryHandlerTest extends TestCase
{
    public function testProfile(): void
    {
        $now = new \DateTimeImmutable('2023-10-23');
        $birthday = new \DateTime('1989-09-17');
        $registrationDate = new \DateTime('2023-09-13 21:00:00');
        $dateUtils = $this->createMock(DateUtilsInterface::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $userRepository
            ->expects(self::once())
            ->method('findProfileByUuid')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([
                [
                    'firstName' => 'Mathieu',
                    'lastName' => 'Marchois',
                    'city' => 'Paris',
                    'biography' => 'Je suis un développeur',
                    'birthday' => $birthday,
                    'registrationDate' => $registrationDate,
                    'displayMyAge' => true,
                ],
            ]);

        $dateUtils
            ->expects(self::once())
            ->method('getNow')
            ->willReturn($now);

        $query = new GetUserProfileQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetUserProfileQueryHandler($userRepository, $dateUtils);

        $this->assertEquals(
            new ProfileView(
                username: 'Mathieu M.',
                city: 'Paris',
                biography: 'Je suis un développeur',
                registrationDate: $registrationDate,
                age: 34,
            ),
            ($handler)($query),
        );
    }

    public function testProfileWithHiddenAge(): void
    {
        $birthday = new \DateTime('1989-09-17');
        $registrationDate = new \DateTime('2023-09-13 21:00:00');
        $dateUtils = $this->createMock(DateUtilsInterface::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $userRepository
            ->expects(self::once())
            ->method('findProfileByUuid')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([
                [
                    'firstName' => 'Mathieu',
                    'lastName' => 'Marchois',
                    'city' => 'Paris',
                    'biography' => 'Je suis un développeur',
                    'birthday' => $birthday,
                    'registrationDate' => $registrationDate,
                    'displayMyAge' => false,
                ],
            ]);

        $dateUtils
            ->expects(self::never())
            ->method('getNow');

        $query = new GetUserProfileQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetUserProfileQueryHandler($userRepository, $dateUtils);

        $this->assertEquals(
            new ProfileView(
                username: 'Mathieu M.',
                city: 'Paris',
                biography: 'Je suis un développeur',
                registrationDate: $registrationDate,
                age: null,
            ),
            ($handler)($query),
        );
    }

    public function testUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $dateUtils = $this->createMock(DateUtilsInterface::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $userRepository
            ->expects(self::once())
            ->method('findProfileByUuid')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([]);

        $dateUtils
            ->expects(self::never())
            ->method('getNow');

        $query = new GetUserProfileQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetUserProfileQueryHandler($userRepository, $dateUtils);
        ($handler)($query);
    }
}
