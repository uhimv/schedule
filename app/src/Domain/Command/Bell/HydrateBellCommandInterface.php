<?php

declare(strict_types=1);

namespace App\Domain\Command\Bell;

interface HydrateBellCommandInterface
{
    public function getId(): string;
    public function getName(): string;
}
