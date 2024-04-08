<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Stdlib\ArrayObject;

final class PageEntity extends ArrayObject implements Db\EntityInterface
{

    public function fromArray(array $data): void { }
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }
}
