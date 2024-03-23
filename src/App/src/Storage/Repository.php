<?php

declare(strict_types=1);

namespace App\Storage;

final class Repository extends AbstractRepository
{
    public function __construct(
        ?string $name,
        ?string $path,
    ) {
        $this->setName($name);
    }
}
