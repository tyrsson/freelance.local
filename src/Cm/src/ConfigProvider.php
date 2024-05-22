<?php

declare(strict_types=1);

namespace Cm;

/**
 * The configuration provider for the Cm module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                Storage\ListItemsRepository::class => Storage\ListItemsRepositoryFactory::class,
                Storage\ListRepository::class      => Storage\ListRepositoryFactory::class,
                Storage\PageDataRepository::class  => Storage\PageDataRepositoryFactory::class,
                Storage\PageRepository::class      => Storage\PageRepositoryFactory::class,
                Storage\PartialRepository::class   => Storage\PartialRepositoryFactory::class,
                Storage\SettingsRepository::class  => Storage\SettingsRepositoryFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'cm'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}