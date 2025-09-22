<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\Teacher\HydrateTeacherCommand;
use App\Domain\Entity\Teacher;

class TeacherMapper
{
    public function fromArray(array $data): Teacher
    {
        return Teacher::hydrate(
            new HydrateTeacherCommand(
                $data['id'],
                $data['name']
            )
        );
    }
}
