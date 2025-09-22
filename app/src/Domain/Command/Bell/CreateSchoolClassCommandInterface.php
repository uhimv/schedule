<?php

declare(strict_types=1);

namespace App\Domain\Command\Bell;

interface CreateSchoolClassCommandInterface
{
    public function getName(): string;
}
