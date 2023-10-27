<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Application\IdFactoryInterface;
use App\Domain\Post\Post;
use App\Domain\User\User;
use App\Infrastructure\Security\SymfonyUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PostCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly IdFactoryInterface $idFactory,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createEntity(string $entityFqcn): Post
    {
        /** @var SymfonyUser */
        $symfonyUser = $this->getUser();
        $user = $this->entityManager->getReference(User::class, $symfonyUser->getUuid());

        return new Post($this->idFactory->make(), $user);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('admin.posts.form.title', [], 'admin')),
            SlugField::new('slug')->setTargetFieldName('title'),
            TextEditorField::new('description')
                ->setLabel($this->translator->trans('admin.posts.form.description', [], 'admin'))
                ->hideOnIndex(),
            DateField::new('publicationDate')
                ->setLabel($this->translator->trans('admin.posts.form.publicationDate', [], 'admin')),
            BooleanField::new('published')
                ->setLabel($this->translator->trans('admin.posts.form.published', [], 'admin')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('admin.post', [], 'admin'))
            ->setEntityLabelInPlural($this->translator->trans('admin.posts', [], 'admin'))
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Post::class;
    }
}
