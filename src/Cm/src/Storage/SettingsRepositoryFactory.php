<?php

declare(strict_types=1);

namespace Cm\Storage;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\Feature\RowGatewayFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

final class SettingsRepositoryFactory
{
    public function __invoke(ContainerInterface $container): SettingsRepository
    {
        $adapter = $container->get(AdapterInterface::class);
        $em      = $container->get(EventManagerInterface::class);
        return new SettingsRepository(
            new Db\TableGateway(
                'settings',
                $adapter,
                new FeatureSet([new RowGatewayFeature(new SettingsEntity('id', 'settings', $adapter))]),
                new ResultSet()
            ),
        );
    }
}
