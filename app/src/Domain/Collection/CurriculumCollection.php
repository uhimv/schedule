<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Curriculum;

/**
 * @extends AbstractCollection<Curriculum>
 */
class CurriculumCollection extends AbstractCollection
{
    #[\Override]
    public static function getTargetClass(): string
    {
        return Curriculum::class;
    }
}
