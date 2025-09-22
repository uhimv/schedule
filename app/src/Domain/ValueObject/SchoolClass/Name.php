<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\SchoolClass;

use App\Domain\ValueObject\Shared\SingleString;

class Name extends SingleString
{
    protected const MIN_LENGTH = 2;
    protected const MAX_LENGTH = 5;
}
