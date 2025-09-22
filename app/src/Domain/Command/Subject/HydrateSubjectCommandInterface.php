<?php

declare(strict_types=1);

namespace App\Domain\Command\Subject;

interface HydrateSubjectCommandInterface
{
    public function getId(): string;
    public function getName(): string;
}
