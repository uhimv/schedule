<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Subject;
use App\Domain\Exception\NotFoundException;
use App\Domain\ValueObject\Shared\Uuid;

/**
 * @extends AbstractCollection<Subject>
 */
class SubjectCollection extends AbstractCollection
{
    #[\Override]
    public static function getTargetClass(): string
    {
        return Subject::class;
    }

    /**
     * @throws NotFoundException
     */
    public function getById(Uuid $subjectId): Subject
    {
        foreach ($this->items as $item) {
            if ($item->getId()->toBase32() === $subjectId->toBase32()) {
                return $item;
            }
        }

        throw new NotFoundException('Subject not found');
    }
}
