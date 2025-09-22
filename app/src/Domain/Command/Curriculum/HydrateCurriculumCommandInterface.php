<?php

declare(strict_types=1);

namespace App\Domain\Command\Curriculum;

interface HydrateCurriculumCommandInterface
{
    public function getId(): string;
    public function getSubjectId(): string;
    public function getSchoolClassId(): string;
    public function getHoursPerYear(): int;
}
