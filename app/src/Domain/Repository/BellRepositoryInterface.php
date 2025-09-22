<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\BellCollection;

interface BellRepositoryInterface
{
    public function findAll(): BellCollection;
}
