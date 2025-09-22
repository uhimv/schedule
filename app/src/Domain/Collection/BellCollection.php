<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Bell;

/**
 * @extends AbstractCollection<Bell>
 */
class BellCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return Bell::class;
    }
}
