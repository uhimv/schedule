<?php

declare(strict_types=1);

namespace App\Domain\Command\TeacherSubject;

interface HydrateTeacherSubjectCommandInterface
{
    public function getSubjectId(): string;
    public function getTeacherId(): string;
}
