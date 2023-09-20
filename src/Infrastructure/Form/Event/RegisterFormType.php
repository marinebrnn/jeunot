<?php

declare(strict_types=1);

namespace App\Infrastructure\Form\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

final class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'events.register.comment',
                'help' => 'events.register.comment.help',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'events.register.submit',
            ])
        ;
    }
}
