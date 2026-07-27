<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Subject;
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
     * @throws \Exception
     */
    public function getById(Uuid $subjectId): Subject
    {
        foreach ($this->items as $item) {
            if ($item->getId()->toBase32() === $subjectId->toBase32()) {
                return $item;
            }
        }

        throw new \Exception('Subject not found'); // TODO change to domain Exception
    }
}
