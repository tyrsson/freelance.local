<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Psr\Container\ContainerInterface;

final class PageDataRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PageDataRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        return new PageDataRepository(
            new Db\TableGateway(
                'page_data',
                $adapter,
                new FeatureSet(
                    [
                        new Db\Feature\ScrollablePdoResultFeature(),
                        new RowGatewayFeature(
                            new PageDataEntity('id', 'page_data', $adapter)
                        )
                    ]
                )
            )
        );
    }
}
