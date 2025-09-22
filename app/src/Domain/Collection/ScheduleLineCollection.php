<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\DomainService\ScheduleLine;
use App\Domain\Entity\Bell;
use App\Domain\Entity\SchoolClass;
use App\Domain\Entity\Teacher;
use App\Domain\Enum\DayWeek;

/**
 * @extends AbstractCollection<ScheduleLine>
 */
class ScheduleLineCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return ScheduleLine::class;
    }

    public function isClassBusy(SchoolClass $schoolClass, DayWeek $dayWeek, Bell $bell): bool
    {
        foreach ($this->items as $item) {
            if ($item->getSchoolClass()->getId()->toBase32() === $schoolClass->getId()->toBase32()
                && $item->getDayWeek()->value === $dayWeek->value
                && $item->getBell()->getId()->toBase32() === $bell->getId()->toBase32()
            ) {
                return true;
            }
        }

        return false;
    }

    public function isTeacherBusy(Teacher $teacher, DayWeek $dayWeek , Bell $bell): bool
    {
        foreach ($this->items as $item) {
            if ($item->getTeacher()->getId()->toBase32() === $teacher->getId()->toBase32()
                && $item->getDayWeek()->value === $dayWeek->value
                && $item->getBell()->getId()->toBase32() === $bell->getId()->toBase32()
            ) {
                return true;
            }
        }

        return false;
    }
}
