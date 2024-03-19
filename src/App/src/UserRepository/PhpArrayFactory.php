<?php

declare(strict_types=1);

namespace App\UserRepository;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;

final class PhpArrayFactory
{
    public function __invoke(ContainerInterface $container): PhpArray
    {
        if (! $container->has('config')) {
            throw new ServiceNotFoundException('Required service config not found');
        }

        if (! $container->has(UserInterface::class)) {
            throw new ServiceNotFoundException('Required service ' . UserInterface::class . ' not found.');
        }

        $config = $container->get('config')[PhpArray::class];
        if (! isset($config['username']) || ! isset($config['password'])) {
            throw new ServiceNotCreatedException(
                'UserRepository service could not be created due to missing configuration'
            );
        }
        return new PhpArray($config['username'], $config['password'], $container->get(UserInterface::class));
    }
}
