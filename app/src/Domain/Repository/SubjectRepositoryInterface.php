<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\SubjectCollection;

interface SubjectRepositoryInterface
{
    public function findAll(): SubjectCollection;
}
