<?php

declare(strict_types=1);

namespace App\Domain\User\Enum;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case NO_BINARY = 'no-binary';
    case OTHER = 'other';
}
