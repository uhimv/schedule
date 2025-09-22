<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\DomainService\ScheduleLine;

/**
 * @extends AbstractCollection<ScheduleLine>
 */
class ScheduleLineCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return ScheduleLine::class;
    }
}
