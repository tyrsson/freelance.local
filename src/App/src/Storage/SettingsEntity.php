<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db\EntityInterface;
use Laminas\Db\RowGateway\RowGateway;
use Laminas\Stdlib\ArrayObject;

final class SettingsEntity extends RowGateway
{
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }

}
