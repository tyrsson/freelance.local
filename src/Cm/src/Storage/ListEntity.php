<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\RowGateway\RowGateway;

final class ListEntity extends RowGateway
{
    use Db\Feature\RelatedTable\ReferenceProviderTrait;
    use Db\Feature\RelatedTable\RowGatewayTrait;
}
