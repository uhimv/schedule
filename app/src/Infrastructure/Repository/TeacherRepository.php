<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\TeacherCollection;
use App\Domain\Repository\TeacherRepositoryInterface;
use App\Infrastructure\Repository\Mapper\TeacherMapper;
use Doctrine\ORM\EntityManagerInterface;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TeacherMapper $teacherMapper
    ) {
    }

    public function findAll(): TeacherCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery('SELECT id, name FROM teacher')
            ->fetchAllAssociative();

        $teacherCollection = new TeacherCollection();
        foreach ($list as $itemArray) {
            $teacherCollection->add(
                $this->teacherMapper->fromArray($itemArray)
            );
        }

        return $teacherCollection;
    }
}
