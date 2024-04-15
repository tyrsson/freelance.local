<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\RowGateway\RowGateway;

final class PageEntity extends RowGateway
{
    use Db\Feature\RelatedTable\ReferenceProviderTrait;
    use Db\Feature\RelatedTable\RowGatewayTrait;

    public function fromArray(array $data): void { }
    public function getId(): ?int
    {
        return $this->offsetGet('id');
    }
}
