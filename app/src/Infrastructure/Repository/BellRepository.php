<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Collection\BellCollection;
use App\Domain\Repository\BellRepositoryInterface;
use App\Infrastructure\Repository\Mapper\BellMapper;
use Doctrine\ORM\EntityManagerInterface;

class BellRepository implements BellRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BellMapper $bellMapper
    ) {
    }

    public function findAll(): BellCollection
    {
        $list = $this->entityManager
            ->getConnection()
            ->executeQuery('SELECT * FROM bell')
            ->fetchAllAssociative();

        $bellCollection = new BellCollection();
        foreach ($list as $itemArray) {
            $bellCollection->add(
                $this->bellMapper->fromArray($itemArray)
            );
        }

        return $bellCollection;
    }
}
