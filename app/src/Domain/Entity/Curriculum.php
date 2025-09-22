<?php

declare(strict_types=1);

namespace App\Domain\Entity;


use App\Domain\Command\Curriculum\HydrateCurriculumCommandInterface;
use App\Domain\ValueObject\Curriculum\HoursPerYear;
use Symfony\Component\Uid\Uuid;

class Curriculum
{
    private const WEEKS_PER_YEAR = 35;

    private Uuid $id;
    private Uuid $subjectId;
    private Uuid $schoolClassId;
    private HoursPerYear $hoursPerYear;

    private function __construct(Uuid $id, Uuid $subjectId, Uuid $schoolClassId, HoursPerYear $hoursPerYear)
    {
        $this->id = $id;
        $this->subjectId = $subjectId;
        $this->schoolClassId = $schoolClassId;
        $this->hoursPerYear = $hoursPerYear;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSubjectId(): Uuid
    {
        return $this->subjectId;
    }

    public function getSchoolClassId(): Uuid
    {
        return $this->schoolClassId;
    }

    public function getHoursPerYear(): HoursPerYear
    {
        return $this->hoursPerYear;
    }

    public function getHoursPerWeek(): int
    {
        return (int) ceil($this->hoursPerYear->getValue() / self::WEEKS_PER_YEAR); // Todo
    }

    public static function hydrate(HydrateCurriculumCommandInterface $command): self
    {
        return new self(
            Uuid::fromString($command->getId()),
            Uuid::fromString($command->getSubjectId()),
            Uuid::fromString($command->getSchoolClassId()),
            HoursPerYear::fromInt($command->getHoursPerYear())
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toBase32(),
            'subjectId' => $this->subjectId->toBase32(),
            'schoolClassId' => $this->schoolClassId->toBase32(),
            'hoursPerYear' => $this->hoursPerYear->getValue(),
        ];
    }
}
