<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\TeacherSubject;
use Symfony\Component\Uid\Uuid;

/**
 * @extends AbstractCollection<TeacherSubject>
 */
class TeacherSubjectCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return TeacherSubject::class;
    }

    /**
     * @return array<Uuid>
     */
    public function findBySubjectId(Uuid $subjectId): self
    {
        $teacherSubjectCollection = new TeacherSubjectCollection();
        foreach ($this->items as $item) {
            if ($item->getSubjectId()->toBase32() === $subjectId->toBase32()) {
                $teacherSubjectCollection->add($item);
            }
        }

        return $teacherSubjectCollection;
    }
}
