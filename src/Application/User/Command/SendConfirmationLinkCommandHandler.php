<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\DateUtilsInterface;
use App\Application\Mail\User\ConfirmAccountMailInterface;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

final class SendConfirmationLinkCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ConfirmAccountMailInterface $confirmAccountMail,
        private readonly DateUtilsInterface $dateUtils,
    ) {
    }

    public function __invoke(SendConfirmationLinkCommand $command): void
    {
        $user = $this->userRepository->findOneByEmail($command->email);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $expirationDate = $this->dateUtils->getNow()->modify('+10 days');
        $this->confirmAccountMail->send($user->getEmail(), $expirationDate);
    }
}
