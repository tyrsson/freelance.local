<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Join;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\ArraySerializableHydrator;
use Psr\Container\ContainerInterface;

final class PageRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PageRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $em      = $container->get(EventManagerInterface::class);
        $hydrator = new ArraySerializableHydrator();
        $ref = new Db\Feature\RelatedTable\RelatedTableReference();
        $ref->addReference([
            'join_table' => 'page',
            'ref_type'   => Db\Feature\RelatedTable\AbstractTableReference::REF_PARENT,
            'parent_key' => 'pageId',
            'join_type'  => Join::JOIN_INNER,
            'fk'         => 'id',
        ]);
        return new PageRepository(
            new Db\TableGateway(
                'page_data',
                $adapter,
                new FeatureSet(
                    [
                        new Db\Feature\RelatedTableFeature($ref)
                    ]
                ),
                new HydratingResultSet($hydrator, new PageEntity([], PageEntity::ARRAY_AS_PROPS)),
            )
        );
    }
}
