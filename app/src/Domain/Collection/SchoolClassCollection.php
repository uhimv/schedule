<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\SchoolClass;
use App\Domain\Exception\NotFoundException;
use App\Domain\ValueObject\Shared\Uuid;

/**
 * @extends AbstractCollection<SchoolClass>
 */
class SchoolClassCollection extends AbstractCollection
{
    #[\Override]
    public static function getTargetClass(): string
    {
        return SchoolClass::class;
    }

    /**
     * @throws NotFoundException
     */
    public function getById(Uuid $schoolClass): SchoolClass
    {
        foreach ($this->items as $item) {
            if ($item->getId()->toBase32() === $schoolClass->toBase32()) {
                return $item;
            }
        }

        throw new NotFoundException(
            \sprintf('Class with id %s not found', $schoolClass->toBase32())
        );
    }
}
