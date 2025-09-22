<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Teacher;

/**
 * @extends AbstractCollection<Teacher>
 */
class TeacherCollection extends AbstractCollection
{
    public static function getTargetClass(): string
    {
        return Teacher::class;
    }

    public function filterBy(array $teacherIdList): self
    {
        $teacherCollection = new TeacherCollection();
        foreach ($this->items as $item) {
            if (in_array($item->getId(), $teacherIdList)) {
                $teacherCollection->add($item);
            }
        }

        return $teacherCollection;
    }
}
