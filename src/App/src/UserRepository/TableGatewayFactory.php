<?php

declare(strict_types=1);

namespace App\UserRepository;

use App\UserRepository\UserEntity;
use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;

final class TableGatewayFactory
{
    public function __invoke(ContainerInterface $container): TableGateway
    {
        return new TableGateway(
            new Db\TableGateway(
                'user',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    new ReflectionHydrator(),
                    new UserEntity()
                )
            ),
            $container->get(UserInterface::class)
        );
    }
}
