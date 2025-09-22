<?php

declare(strict_types=1);

namespace App\Domain\Command\Bell;

interface CreateBellCommandInterface
{
    public function getName(): string;
}
