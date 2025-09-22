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

    private function classIsBusy(SchoolClass $schoolClass, DayWeek $dayWeek, Bell $bell): bool
    {
        return $this->schedule->isClassBusy($schoolClass, $dayWeek, $bell);
    }

    private function teacherIsBusy(Teacher $teacher, DayWeek $dayWeek , Bell $bell): bool
    {
        return $this->schedule->isTeacherBusy($teacher, $dayWeek, $bell);
    }
}
