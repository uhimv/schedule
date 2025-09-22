<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Mapper;

use App\Domain\Command\Bell\HydrateBellCommand;
use App\Domain\Entity\Bell;

class BellMapper
{
    public function fromArray(array $data): Bell
    {
        return Bell::hydrate(
            new HydrateBellCommand(
                $data['id'],
                $data['name']
            )
        );
    }
}
