<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\TeacherSubject\HydrateTeacherSubjectCommand;
use App\Domain\Entity\TeacherSubject;

class TeacherSubjectMapper
{
    public function fromArray(array $data): TeacherSubject
    {
        return TeacherSubject::hydrate(
            new HydrateTeacherSubjectCommand(
                $data['subject_id'],
                $data['teacher_id']
            )
        );
    }
}
