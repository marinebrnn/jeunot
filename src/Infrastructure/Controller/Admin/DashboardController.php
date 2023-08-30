<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Domain\Event\Event;
use App\Domain\Event\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jeunot');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard($this->translator->trans('admin.dashboard', [], 'admin'), 'fa fa-home');
        yield MenuItem::section($this->translator->trans('admin.events', [], 'admin'));
        yield MenuItem::linkToCrud($this->translator->trans('admin.events', [], 'admin'), 'fa fa-calendar', Event::class);
        yield MenuItem::linkToCrud($this->translator->trans('admin.tags', [], 'admin'), 'fa fa-list', Tag::class);

        yield MenuItem::section('Autres');
        yield MenuItem::linkToRoute('Jeunot', 'fa fa-globe', 'app_home');
    }
}
