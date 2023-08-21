<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Security;

use App\Application\CommandBusInterface;
use App\Application\DateUtilsInterface;
use App\Application\User\Command\ConfirmAccountCommand;
use App\Domain\User\Exception\UserNotFoundException;
use App\Infrastructure\Security\SymfonyUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Annotation\Route;

final readonly class RegisterConfirmAccountController
{
    public function __construct(
        private UriSigner $uriSigner,
        private CommandBusInterface $commandBus,
        private Security $security,
        private DateUtilsInterface $dateUtils,
    ) {
    }

    #[Route('/register/confirm-account', name: 'app_register_confirm_account', methods: ['GET'])]
    public function __invoke(
        Request $request,
        #[MapQueryParameter] string $email,
        #[MapQueryParameter] int $expirationDate,
    ): Response {
        if (false === $this->uriSigner->checkRequest($request)) {
            throw new BadRequestHttpException('Unsigned URI');
        }

        if ($this->dateUtils->getNow()->getTimestamp() > $expirationDate) {
            throw new BadRequestHttpException('Expired URI');
        }

        try {
            $user = $this->commandBus->handle(new ConfirmAccountCommand($email));
            $redirectResponse = $this->security->login(new SymfonyUser($user));

            return $redirectResponse;
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException();
        }
    }
}
