<?php

declare(strict_types=1);

namespace App\Domain\Command\Subject;

class HydrateSubjectCommand implements HydrateSubjectCommandInterface
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
