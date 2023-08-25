<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Security;

use App\Application\CommandBusInterface;
use App\Application\DateUtilsInterface;
use App\Application\User\Command\ConfirmAccountCommand;
use App\Domain\User\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class RegisterConfirmAccountController
{
    public function __construct(
        private UriSigner $uriSigner,
        private CommandBusInterface $commandBus,
        private UrlGeneratorInterface $urlGenerator,
        private DateUtilsInterface $dateUtils,
        private TranslatorInterface $translator,
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
            /** @var FlashBagAwareSessionInterface */
            $session = $request->getSession();
            $this->commandBus->handle(new ConfirmAccountCommand($email));
            $session->getFlashBag()->add('success', $this->translator->trans('register.succeeded.verified'));

            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException();
        }
    }
}
