<?php

declare(strict_types=1);

namespace App\Domain\Command\Teacher;

interface HydrateTeacherCommandInterface
{
    public function getId(): string;
    public function getName(): string;
}
