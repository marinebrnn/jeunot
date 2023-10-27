<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Domain\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName')
                ->setLabel($this->translator->trans('admin.users.firstName', [], 'admin')),
            TextField::new('lastName')
                ->setLabel($this->translator->trans('admin.users.lastName', [], 'admin')),
            EmailField::new('email')
                ->setLabel($this->translator->trans('admin.users.email', [], 'admin')),
            TextField::new('city')
                ->setLabel($this->translator->trans('admin.users.location', [], 'admin')),
            TextField::new('howYouHeardAboutUs')
                ->setLabel($this->translator->trans('admin.users.howYouHeardAboutUs', [], 'admin')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('admin.user', [], 'admin'))
            ->setEntityLabelInPlural($this->translator->trans('admin.users', [], 'admin'))
        ;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }
}
