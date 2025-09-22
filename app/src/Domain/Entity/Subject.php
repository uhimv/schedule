<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Command\Subject\HydrateSubjectCommandInterface;
use App\Domain\ValueObject\Subject\Name;
use Symfony\Component\Uid\Uuid; // TODO dont use in Domain

class Subject
{
    private Uuid $id;

    private Name $name;

    private function __construct(Uuid $id, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public static function hydrate(HydrateSubjectCommandInterface $command): self
    {
        return new self(
            Uuid::fromString($command->getId()),
            Name::fromString($command->getName())
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toBase32(),
            'name' => $this->name->getValue(),
        ];
    }
}
