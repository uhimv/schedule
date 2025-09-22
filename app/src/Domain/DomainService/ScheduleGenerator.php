<?php

declare(strict_types=1);

namespace App\Domain\DomainService;

use App\Domain\Collection\BellCollection;
use App\Domain\Collection\CurriculumCollection;
use App\Domain\Collection\ScheduleLineCollection;
use App\Domain\Collection\SchoolClassCollection;
use App\Domain\Collection\SubjectCollection;
use App\Domain\Collection\TeacherCollection;
use App\Domain\Collection\TeacherSubjectCollection;
use App\Domain\Entity\Bell;
use App\Domain\Entity\Curriculum;
use App\Domain\Entity\SchoolClass;
use App\Domain\Entity\Subject;
use App\Domain\Entity\Teacher;
use App\Domain\Enum\DayWeek;
use Symfony\Component\Uid\Uuid;

class ScheduleGenerator
{
    private ScheduleLineCollection $schedule;

    /**
     * @var array<string, array<int|string, array<string, bool>>> - [teacherId][dayWeekValue][bellId]
     */
    private array $teacherBusy = [];

    /**
     * @var array<string, array<int|string, array<string, bool>>> - [schoolClassId][dayWeekValue][bellId]
     */
    private array $classBusy = [];

    public function __construct(
        private BellCollection $bellCollection,
        private CurriculumCollection $curriculumCollection,
        private SchoolClassCollection $schoolClassCollection,
        private SubjectCollection $subjectCollection,
        private TeacherCollection $teacherCollection,
        private TeacherSubjectCollection $teacherSubjectCollection
    ) {
        $this->schedule = new ScheduleLineCollection();
    }

    public function generate(): ScheduleLineCollection
    {
        $curriculumArray = [];
        /** @var Curriculum $curriculum */
        foreach ($this->curriculumCollection as $curriculum) {
            for ($i = 0; $i <= $curriculum->getHoursPerWeek(); $i++) {
                $curriculumArray[] = [
                    'subjectId' => $curriculum->getSubjectId(),
                    'schoolClassId' => $curriculum->getSchoolClassId(),
                ];
            }
        }
        shuffle($curriculumArray);

        foreach ($curriculumArray as $curriculum) {
            $this->scheduleSubjectForClass($curriculum['subjectId'], $curriculum['schoolClassId']);
        }

        return $this->schedule;
    }

    private function scheduleSubjectForClass(Uuid $subjectId, Uuid $schoolClassId): void
    {
        $subject = $this->subjectCollection->getById($subjectId);
        $schoolClass = $this->schoolClassCollection->getById($schoolClassId);

        $availableTeachers = $this->findTeachersForSubject($subject);

        foreach (DayWeek::cases() as $dayWeek) {
            foreach ($this->bellCollection as $bell) {
                foreach ($availableTeachers as $teacher) {
                    if ($this->tryScheduleSlot($schoolClass, $subject, $teacher, $dayWeek, $bell)) {
                        return;
                    }
                }
            }
        }
    }

    private function tryScheduleSlot(SchoolClass $schoolClass, Subject $subject, Teacher $teacher, DayWeek $dayWeek, Bell $bell): bool
    {
        if (
            !$this->teacherIsBusy($teacher, $dayWeek, $bell)
            && !$this->classIsBusy($schoolClass, $dayWeek, $bell)
        ) {
            $this->reserveTeacher($teacher, $dayWeek, $bell);
            $this->reserveClass($schoolClass, $dayWeek, $bell);

            $this->schedule->add(
                new ScheduleLine(
                    $dayWeek,
                    $bell,
                    $schoolClass,
                    $subject,
                    $teacher
                )
            );
            return true;
        }
        return false;
    }

    private function findTeachersForSubject(Subject $subject): TeacherCollection
    {
        $teacherSubjectCollection =  $this->teacherSubjectCollection->findBySubjectId($subject->getId());
        $teacherIdList = [];
        foreach ($teacherSubjectCollection as $teacherSubject) {
            $teacherIdList[] = $teacherSubject->getTeacherId();
        }

        return  $this->teacherCollection->filterBy($teacherIdList);
    }

    private function classIsBusy(SchoolClass $schoolClass, $dayWeek, $bell): bool
    {
        return isset($this->classBusy[$schoolClass->getId()->toBase32()][$dayWeek->value][$bell->getId()->toBase32()]);
    }

    private function reserveClass(SchoolClass $schoolClass, DayWeek $dayWeek, Bell $bell): void
    {
        if ($this->classIsBusy($schoolClass, $dayWeek, $bell)) {
            throw new \Exception(
                \sprintf(
                    'Class: %s is busy on %s %s',
                    $schoolClass->getName()->getValue(),
                    $dayWeek->value,
                    $bell->getName()->getValue()
                )
            );
        }

        $this->classBusy[$schoolClass->getId()->toBase32()][$dayWeek->value][$bell->getId()->toBase32()] = true;
    }

    private function teacherIsBusy(Teacher $teacher, DayWeek $day , Bell $bell): bool
    {
        return isset($this->teacherBusy[$teacher->getId()->toBase32()][$day->value][$bell->getId()->toBase32()]);
    }

    private function reserveTeacher(Teacher $teacher, DayWeek $dayWeek, Bell $bell): void
    {
        if ($this->teacherIsBusy($teacher, $dayWeek, $bell)) {
            throw new \Exception(
                \sprintf(
                    'Teacher: %s is busy on %s %s',
                    $teacher->getName()->getValue(),
                    $dayWeek->value,
                    $bell->getName()->getValue()
                )
            );
        }

        $this->teacherBusy[$teacher->getId()->toBase32()][$dayWeek->value][$bell->getId()->toBase32()] = true;
    }
}
