<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Db\Sql\Join;

final class PartialTableReference extends Db\Feature\RelatedTable\AbstractTableReference
{
    protected array $referenceMap = [
        [
            'join_table'  => 'tpl_partial',
            'ref_type'    => self::REF_PARENT,
            'parent_key'   => 'partialId',
            'join_type'   => Join::JOIN_INNER,
            'fk'          => 'id'
        ],
    ];
}
