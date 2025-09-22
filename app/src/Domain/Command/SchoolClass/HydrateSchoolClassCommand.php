<?php

declare(strict_types=1);

namespace App\Domain\Command\SchoolClass;

class HydrateSchoolClassCommand implements HydrateSchoolClassCommandInterface
{
    public function __construct(private string $id, private string $name)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
