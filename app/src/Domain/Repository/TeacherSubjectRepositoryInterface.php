<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\TeacherSubjectCollection;

interface TeacherSubjectRepositoryInterface
{
    public function findAll(): TeacherSubjectCollection;
}
