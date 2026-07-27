<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\TeacherSubjectCollection;
use App\Domain\Repository\TeacherSubjectRepositoryInterface;
use App\Infrastructure\Repository\Mapper\TeacherSubjectMapper;
use Doctrine\ORM\EntityManagerInterface;

class TeacherSubjectRepository implements TeacherSubjectRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeacherSubjectMapper $schoolClassMapper
    ) {
    }

    #[\Override]
    public function findAll(): TeacherSubjectCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery(
                'SELECT BIN_TO_UUID(teacher_id) AS teacher_id, BIN_TO_UUID(subject_id) AS subject_id '
                . 'FROM teacher_subject'
            )
            ->fetchAllAssociative();

        $teacherSubjectCollection = new TeacherSubjectCollection();
        foreach ($list as $itemArray) {
            $teacherSubjectCollection->add(
                $this->schoolClassMapper->fromArray($itemArray)
            );
        }

        return $teacherSubjectCollection;
    }
}
