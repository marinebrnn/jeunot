<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Application\IdFactoryInterface;
use App\Domain\Event\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TagCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly IdFactoryInterface $idFactory,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createEntity(string $entityFqcn): Tag
    {
        return new Tag($this->idFactory->make());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('admin.tags.form.title', [], 'admin')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('admin.tag', [], 'admin'))
            ->setEntityLabelInPlural($this->translator->trans('admin.tags', [], 'admin'))
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }
}
