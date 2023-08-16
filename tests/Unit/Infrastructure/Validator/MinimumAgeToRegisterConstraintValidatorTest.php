<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Validation;

use App\Application\DateUtilsInterface;
use App\Infrastructure\Validator\MinimumAgeToRegisterConstraint;
use App\Infrastructure\Validator\MinimumAgeToRegisterConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class MinimumAgeToRegisterConstraintValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        $dateUtils = $this->createMock(DateUtilsInterface::class);

        return new MinimumAgeToRegisterConstraintValidator($dateUtils, 55);
    }

    public function testUnexpectedConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('', new NotBlank());
    }

    public function testBadTypeConstraint(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->assertEmpty($this->validator->validate('date', new MinimumAgeToRegisterConstraint()));
    }

    // Other cases are covered in integration tests.
}
