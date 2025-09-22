<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\SchoolClass;
use Symfony\Component\Uid\Uuid;

/**
 * @extends AbstractCollection<SchoolClass>
 */
class SchoolClassCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return SchoolClass::class;
    }

    /**
     * @throws \Exception
     */
    public function getById(Uuid $schoolClass): SchoolClass
    {
        foreach ($this->items as $item) {
            if ($item->getId()->toBase32() === $schoolClass->toBase32()) {
                return $item;
            }
        }

        throw new \Exception( // TODO change to domain Exception
            \sprintf('Class with id %s not found', $schoolClass->toBase32())
        );
    }
}
