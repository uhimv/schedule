<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Collection\TeacherCollection;

interface TeacherRepositoryInterface
{
    public function findAll(): TeacherCollection;
}
