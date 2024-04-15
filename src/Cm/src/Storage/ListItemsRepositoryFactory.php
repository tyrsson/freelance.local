<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Psr\Container\ContainerInterface;

final class ListItemsRepositoryFactory
{
    public function __invoke(ContainerInterface $container): ListItemsRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        return new ListItemsRepository(
            new Db\TableGateway(
                'list_items',
                $adapter,
                new FeatureSet([
                    new Db\Feature\ScrollablePdoResultFeature(),
                    new RowGatewayFeature(
                        new ListItemsEntity('id', 'list_items', $adapter)
                    )
                ])
            )
        );
    }
}
