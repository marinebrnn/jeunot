<?php

declare(strict_types=1);

namespace App\Infrastructure\Validator;

use App\Application\DateUtilsInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class MinimumAgeToRegisterConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly DateUtilsInterface $dateUtils,
        private readonly int $minimumAgeToRegister,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MinimumAgeToRegisterConstraint) {
            throw new UnexpectedTypeException($constraint, MinimumAgeToRegisterConstraint::class);
        }

        if (!$value || !$value instanceof \DateTimeInterface) {
            throw new UnexpectedValueException($value, 'date');
        }

        $userYear = (int) $value->format('Y');
        $nowYear = (int) $this->dateUtils->getCurrentYear();

        if ($nowYear - $userYear < $this->minimumAgeToRegister) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
