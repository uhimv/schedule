<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\SchoolClassCollection;

interface SchoolClassRepositoryInterface
{
    public function findAll(): SchoolClassCollection;
}
