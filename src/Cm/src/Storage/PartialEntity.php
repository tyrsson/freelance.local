<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Stdlib\ArrayObject;

final class PartialEntity extends ArrayObject implements Db\EntityInterface
{
    public function fromArray(array $data): void { }
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }
}
