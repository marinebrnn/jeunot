<?php

declare(strict_types=1);

namespace App\Infrastructure\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class RegisterUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'register.firstName',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'register.lastName',
                'help' => 'register.lastName.help',
            ])
            ->add('email', EmailType::class, [
                'label' => 'register.email',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'register.password',
                    'help' => 'register.password.help',
                ],
                'second_options' => [
                    'label' => 'register.repeated_password',
                    'help' => 'register.repeated_password.help',
                ],
            ])
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => 'register.birthday',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'register.submit',
            ])
        ;
    }
}
