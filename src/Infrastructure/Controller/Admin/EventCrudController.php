<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Application\IdFactoryInterface;
use App\Domain\Event\Event;
use App\Domain\User\User;
use App\Infrastructure\Security\SymfonyUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EventCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly IdFactoryInterface $idFactory,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createEntity(string $entityFqcn): Event
    {
        /** @var SymfonyUser */
        $symfonyUser = $this->getUser();
        $user = $this->entityManager->getReference(User::class, $symfonyUser->getUuid());

        return new Event($this->idFactory->make(), $user);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('admin.events.form.title', [], 'admin')),
            TextEditorField::new('description')
                ->setLabel($this->translator->trans('admin.events.form.description', [], 'admin'))
                ->hideOnIndex(),
            DateTimeField::new('startDate')
                ->setLabel($this->translator->trans('admin.events.form.startDate', [], 'admin')),
            DateTimeField::new('endDate')
                ->setLabel($this->translator->trans('admin.events.form.endDate', [], 'admin')),
            IntegerField::new('initialAvailablePlaces')
                ->setLabel($this->translator->trans('admin.events.form.initialAvailablePlaces', [], 'admin'))
                ->hideOnIndex(),
            TextField::new('location')
                ->setLabel($this->translator->trans('admin.events.form.location', [], 'admin')),
            AssociationField::new('tags')
                ->setLabel($this->translator->trans('admin.tags', [], 'admin'))
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('choice_label', 'title'),
            BooleanField::new('published')
                ->setLabel($this->translator->trans('admin.events.form.published', [], 'admin')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('admin.event', [], 'admin'))
            ->setEntityLabelInPlural($this->translator->trans('admin.events', [], 'admin'))
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }
}
