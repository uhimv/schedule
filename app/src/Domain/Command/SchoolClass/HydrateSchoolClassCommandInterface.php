<?php

declare(strict_types=1);

namespace App\Domain\Command\SchoolClass;

interface HydrateSchoolClassCommandInterface
{
    public function getId(): string;
    public function getName(): string;
}
