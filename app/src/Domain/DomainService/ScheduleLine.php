<?php

declare(strict_types=1);

namespace App\Domain\DomainService;

use App\Domain\Entity\Bell;
use App\Domain\Entity\SchoolClass;
use App\Domain\Entity\Subject;
use App\Domain\Entity\Teacher;
use App\Domain\Enum\DayWeek;

class ScheduleLine
{
    public function __construct(
        private DayWeek $dayWeek,
        private Bell $bell,
        private SchoolClass $schoolClass,
        private Subject $subject,
        private Teacher $teacher
    ) {
    }

    public function getDayWeek(): DayWeek
    {
        return $this->dayWeek;
    }

    public function getBell(): Bell
    {
        return $this->bell;
    }

    public function getSchoolClass(): SchoolClass
    {
        return $this->schoolClass;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }
}
