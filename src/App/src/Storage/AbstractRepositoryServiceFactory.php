<?php

declare(strict_types=1);

namespace App\Storage;

use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;

use function is_array;

final class AbstractRepositoryServiceFactory implements AbstractFactoryInterface
{
        /** @var array */
        protected $config;

        public function canCreate(ContainerInterface $container, $requestedName)
        {
            $config = $this->getConfig($container);
            if (empty($config)) {
                return false;
            }

            return isset($config[$requestedName])
            && is_array($config[$requestedName])
            && ! empty($config[$requestedName]);
        }

        public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
        {
            $config = $this->getConfig($container);
            return new Repository(
                name: $config[$requestedName]['name'],
                path: $config[$requestedName]['path']
            );
        }

        private function getConfig(ContainerInterface $container): array
        {
            // if this has been set return
            if ($this->config !== null) {
                return $this->config;
            }

            // if we do not have a config service return an empty array
            if (! $container->has('config')) {
                $this->config = [];
                return $this->config;
            }

            $config = $container->get('config');
            if (
                ! isset($config[RepositoryInterface::class])
                || ! is_array($config[RepositoryInterface::class])
            ) {
                $this->config = [];
                return $this->config;
            }

            $config = $config[RepositoryInterface::class];
            if (
                ! isset($config[RepositoryInterface::ABSTRACT_FACTORY_KEY])
                || ! is_array($config[RepositoryInterface::ABSTRACT_FACTORY_KEY])
            ) {
                $this->config = [];
                return $this->config;
            }

            $this->config = $config[RepositoryInterface::ABSTRACT_FACTORY_KEY];
            return $this->config;
        }
}
