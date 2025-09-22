<?php

declare(strict_types=1);

namespace App\Domain\Command\TeacherSubject;

class HydrateTeacherSubjectCommand implements HydrateTeacherSubjectCommandInterface
{
    public function __construct(private string $subjectId, private string $teacherId)
    {
    }

    public function getSubjectId(): string
    {
        return $this->subjectId;
    }

    public function getTeacherId(): string
    {
        return $this->teacherId;
    }
}
