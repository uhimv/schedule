<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Command\Teacher\HydrateTeacherCommandInterface;
use App\Domain\ValueObject\Teacher\Name;
use Symfony\Component\Uid\Uuid;

class Teacher
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

    public static function hydrate(HydrateTeacherCommandInterface $command): self
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
