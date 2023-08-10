<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class MinimumAgeToRegisterConstraint extends Constraint
{
    public $message = 'register.error.minimum_age_to_register';
}
