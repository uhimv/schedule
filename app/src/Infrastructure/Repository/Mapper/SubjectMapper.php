<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\Subject\HydrateSubjectCommand;
use App\Domain\Entity\Subject;

class SubjectMapper
{
    public function fromArray(array $data): Subject
    {
        return Subject::hydrate(
            new HydrateSubjectCommand(
                $data['id'],
                $data['name']
            )
        );
    }
}
