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

final class PageRepositoryFactory
{
    public function __invoke(ContainerInterface $container): PageRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $em      = $container->get(EventManagerInterface::class);
        return new PageRepository(
            new Db\TableGateway(
                'page',
                $adapter,
                new FeatureSet([new RowGatewayFeature(new PageEntity('id', 'page', $adapter))]),
                new ResultSet()
            )
        );
    }
}
