<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Sql\Join;

final class PartialTableReference extends Db\Feature\RelatedTable\AbstractTableReference
{
    protected array $referenceMap = [
        [
            'dependent_table'  => 'tpl_partial_data',
            'ref_type'    => self::REF_DEPENDENT,
            'parent_table' => 'tpl_partial',
            'parent_pk'   => 'id',
            'join_type'   => Join::JOIN_INNER,
            'fk'          => 'partialId'
        ],
    ];
}
