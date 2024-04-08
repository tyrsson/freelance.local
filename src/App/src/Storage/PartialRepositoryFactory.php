<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\ArraySerializableHydrator;
use Psr\Container\ContainerInterface;

final class PartialRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PartialRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $em      = $container->get(EventManagerInterface::class);
        $hydrator = new ArraySerializableHydrator();
        $repo = new PartialRepository(
            new Db\TableGateway(
                'tpl_partial_data',
                $adapter,
                new FeatureSet(
                    [
                        //new RowGatewayFeature(new PartialEntity('id', 'tpl_partial', $adapter)),
                        new Db\Feature\RelatedTableFeature(new PartialTableReference())
                    ]
                ),
                new HydratingResultSet(
                    $hydrator,
                    new PartialEntity([], PartialEntity::ARRAY_AS_PROPS)
                ),
                null,
                $em,
                new Listener\PartialListener()
            )
        );
        $repo->setHydrator($hydrator);
        return $repo;
    }
}
