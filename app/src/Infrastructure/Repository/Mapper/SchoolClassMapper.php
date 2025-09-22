<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\SchoolClass\HydrateSchoolClassCommand;
use App\Domain\Entity\SchoolClass;

class SchoolClassMapper
{
    public function fromArray(array $data): SchoolClass
    {
        return SchoolClass::hydrate(
            new HydrateSchoolClassCommand(
                $data['id'],
                $data['name']
            )
        );
    }
}
