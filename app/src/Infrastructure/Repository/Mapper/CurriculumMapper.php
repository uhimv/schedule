<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\Curriculum\HydrateCurriculumCommand;
use App\Domain\Entity\Curriculum;

class CurriculumMapper
{
    public function fromArray(array $data): Curriculum
    {
        return Curriculum::hydrate(
            new HydrateCurriculumCommand(
                $data['id'],
                $data['subject_id'],
                $data['school_class_id'],
                $data['hours_per_year']
            )
        );
    }
}
