<?php

declare(strict_types=1);

namespace App\Application\UseCase\Schedule;

use App\Domain\DomainService\ScheduleGenerator;
use App\Domain\DomainService\ScheduleLine;
use App\Domain\Repository\BellRepositoryInterface;
use App\Domain\Repository\CurriculumRepositoryInterface;
use App\Domain\Repository\SchoolClassRepositoryInterface;
use App\Domain\Repository\SubjectRepositoryInterface;
use App\Domain\Repository\TeacherRepositoryInterface;
use App\Domain\Repository\TeacherSubjectRepositoryInterface;

class GenerateHandler
{
    public function __construct(
        private BellRepositoryInterface $bellRepository,
        private CurriculumRepositoryInterface $curriculumRepository,
        private SchoolClassRepositoryInterface $schoolClassRepository,
        private SubjectRepositoryInterface $subjectRepository,
        private TeacherRepositoryInterface $teacherRepository,
        private TeacherSubjectRepositoryInterface $teacherSubjectRepository
    ) {
    }

    public function handle(): array
    {
        $bellCollection = $this->bellRepository->findAll();
        $curriculumCollection = $this->curriculumRepository->findAll();
        $schoolClassCollection = $this->schoolClassRepository->findAll();
        $subjectCollection = $this->subjectRepository->findAll();
        $teacherCollection = $this->teacherRepository->findAll();
        $teacherSubjectCollection = $this->teacherSubjectRepository->findAll();

        $scheduleGenerator = new ScheduleGenerator(
            $bellCollection,
            $curriculumCollection,
            $schoolClassCollection,
            $subjectCollection,
            $teacherCollection,
            $teacherSubjectCollection
        );

        $scheduleArray = [];
        /** @var ScheduleLine $line */
        foreach ($scheduleGenerator->generate() as $line) {
            $scheduleArray[] = [
                'dayWeek' => $line->getDayWeek()->name,
                'bell' => $line->getBell()->getName()->getValue(),
                'schoolClass' => $line->getSchoolClass()->getName()->getValue(),
                'subject' => $line->getSubject()->getName()->getValue(),
                'teacher' => $line->getTeacher()->getName()->getValue(),
            ];
        }

        return $scheduleArray;
    }
}
