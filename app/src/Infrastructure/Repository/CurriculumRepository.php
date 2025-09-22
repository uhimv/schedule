<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\CurriculumCollection;
use App\Domain\Repository\CurriculumRepositoryInterface;
use App\Infrastructure\Repository\Mapper\CurriculumMapper;
use Doctrine\ORM\EntityManagerInterface;

class CurriculumRepository implements CurriculumRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CurriculumMapper $curriculumMapper
    ) {
    }

    public function findAll(): CurriculumCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery('SELECT id, subject_id, school_class_id, hours_per_year FROM curriculum')
            ->fetchAllAssociative();

        $curriculumCollection = new CurriculumCollection();
        foreach ($list as $itemArray) {
            $curriculumCollection->add(
                $this->curriculumMapper->fromArray($itemArray)
            );
        }

        return $curriculumCollection;
    }
}
