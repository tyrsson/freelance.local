<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Psr\Container\ContainerInterface;

final class ListRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ListRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $ref     = new Db\Feature\RelatedTable\RelatedTableReference;
        $ref->addReference([
            'dependent_table' => $container->get(ListItemsRepository::class),
            'ref_type'        => Db\Feature\RelatedTable\ReferenceInterface::REF_DEPENDENT,
            'column_map'      => ['local' => 'id', 'fk' => 'listId'],
        ]);
        $rowGatewayPrototype = new ListEntity('id', 'list', $adapter);
        $rowGatewayPrototype->setReferenceProvider($ref);
        return new ListRepository(
            new Db\TableGateway(
                'list',
                $adapter,
                new FeatureSet([
                    new Db\Feature\ScrollablePdoResultFeature(),
                    new RowGatewayFeature($rowGatewayPrototype),
                ])
            )
        );
    }
}
