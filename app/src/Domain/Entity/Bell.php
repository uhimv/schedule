<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Command\Bell\CreateBellCommandInterface;
use App\Domain\Command\Bell\HydrateBellCommandInterface;
use App\Domain\ValueObject\Bell\Name;
use Symfony\Component\Uid\Uuid;

class Bell
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

    public static function create(CreateBellCommandInterface $command): self
    {
        return new self(
            Uuid::v7(),
            Name::fromString($command->getName())
        );
    }

    public static function hydrate(HydrateBellCommandInterface $command): self
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
