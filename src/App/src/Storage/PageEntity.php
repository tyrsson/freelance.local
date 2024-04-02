<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Db\RowGateway\RowGateway;

final class PageEntity extends RowGateway implements Db\EntityInterface
{

    public function fromArray(array $data): void { }
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }
}
