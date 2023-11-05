<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Admin;

use App\Application\IdFactoryInterface;
use App\Application\StorageInterface;
use App\Domain\Post\Post;
use App\Domain\User\User;
use App\Infrastructure\Security\SymfonyUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PostCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly IdFactoryInterface $idFactory,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
        private readonly StorageInterface $storage,
        private readonly string $storageCdn,
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
            ImageField::new('picture')
                ->setLabel($this->translator->trans('admin.posts.form.picture', [], 'admin'))
                ->setHelp($this->translator->trans('admin.images.format', [], 'admin'))
                ->setUploadDir('/var/')
                ->setBasePath($this->storageCdn),
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

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        return $this->addPictureEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        return $this->addPictureEventListener($formBuilder);
    }

    private function addPictureEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function ($event) {
            $form = $event->getForm();
            $data = $form->getData();

            if (!$form->isValid()) {
                return;
            }

            $file = $form->get('picture')->getNormData();
            if ($file) {
                if ($data->getPicture()) {
                    $this->storage->delete($data->getPicture());
                }

                $picture = $this->storage->write($data->getUuid(), $file);
                $data->setPicture($picture);
            }
        });
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
