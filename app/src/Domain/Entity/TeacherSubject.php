<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Command\TeacherSubject\HydrateTeacherSubjectCommandInterface;
use Symfony\Component\Uid\Uuid;

class TeacherSubject
{
    private function __construct(private Uuid $subjectId, private Uuid $teacherId)
    {
    }

    public function getSubjectId(): Uuid
    {
        return $this->subjectId;
    }

    public function getTeacherId(): Uuid
    {
        return $this->teacherId;
    }

    public static function hydrate(HydrateTeacherSubjectCommandInterface $command): self
    {
        return new self(
            Uuid::fromString($command->getSubjectId()),
            Uuid::fromString($command->getTeacherId())
        );
    }

    public function toArray(): array
    {
        return [
            'subjectId' => $this->subjectId->toBase32(),
            'teacherId' => $this->teacherId->toBase32(),
        ];
    }
}
