<?php

declare(strict_types=1);

namespace App\Infrastructure\Form\User;

use App\Domain\User\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];

        foreach (GenderEnum::cases() as $case) {
            $choices[sprintf('profile.gender.%s', $case->value)] = $case->value;
        }

        $builder
            ->add('file', FileType::class, [
                'required' => false,
                'label' => 'profile.file',
                'help' => 'profile.file.help',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'profile.firstName',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'profile.lastName',
            ])
            ->add('email', EmailType::class, [
                'label' => 'profile.email',
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'profile.gender',
                'choices' => $choices,
                'placeholder' => 'profile.gender.placeholder',
            ])
            ->add('city', TextType::class, [
                'label' => 'profile.city',
            ])
            ->add('displayMyAge', CheckboxType::class, [
                'label' => 'profile.displayMyAge',
                'required' => false,
            ])
            ->add('biography', TextareaType::class, [
                'label' => 'profile.biography',
            ])
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => 'profile.birthday',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'profile.submit',
            ])
        ;
    }
}
