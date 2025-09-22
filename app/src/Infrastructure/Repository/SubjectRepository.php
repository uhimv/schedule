<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\SubjectCollection;
use App\Domain\Repository\SubjectRepositoryInterface;
use App\Infrastructure\Repository\Mapper\SubjectMapper;
use Doctrine\ORM\EntityManagerInterface;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SubjectMapper $schoolClassMapper
    ) {
    }

    public function findAll(): SubjectCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery('SELECT id, name FROM subject')
            ->fetchAllAssociative();

        $subjectCollection = new SubjectCollection();
        foreach ($list as $itemArray) {
            $subjectCollection->add(
                $this->schoolClassMapper->fromArray($itemArray)
            );
        }

        return $subjectCollection;
    }
}
