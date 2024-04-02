<?php

declare(strict_types=1);

namespace App\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

final class PartialRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PartialRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $em      = $container->get(EventManagerInterface::class);
        return new PartialRepository(
            new Db\TableGateway(
                'tpl_partial_data',
                $adapter,
                new FeatureSet(
                    [
                        new RowGatewayFeature(new PartialEntity('id', 'tpl_partial', $adapter)),
                        new Db\Feature\RelatedTableFeature(new PartialTableReference())
                    ]
                ),
                new ResultSet(),
                null,
                $em,
                new Listener\PartialListener()
            )
        );
    }
}
