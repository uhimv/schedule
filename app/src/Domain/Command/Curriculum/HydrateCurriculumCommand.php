<?php

declare(strict_types=1);

namespace App\Domain\Command\Curriculum;

class HydrateCurriculumCommand implements HydrateCurriculumCommandInterface
{
    public function __construct(
        private string $id,
        private string $subjectId,
        private string $schoolClassId,
        private int $hoursPerYear,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSubjectId(): string
    {
        return $this->subjectId;
    }

    public function getSchoolClassId(): string
    {
        return $this->schoolClassId;
    }

    public function getHoursPerYear(): int
    {
        return $this->hoursPerYear;
    }
}
