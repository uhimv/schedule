<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\SchoolClassCollection;
use App\Domain\Repository\SchoolClassRepositoryInterface;
use App\Infrastructure\Repository\Mapper\SchoolClassMapper;
use Doctrine\ORM\EntityManagerInterface;

class SchoolClassRepository implements SchoolClassRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SchoolClassMapper $schoolClassMapper
    ) {
    }

    public function findAll(): SchoolClassCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery('SELECT id, name FROM school_class')
            ->fetchAllAssociative();

        $schoolClassCollection = new SchoolClassCollection();
        foreach ($list as $itemArray) {
            $schoolClassCollection->add(
                $this->schoolClassMapper->fromArray($itemArray)
            );
        }

        return $schoolClassCollection;
    }
}
