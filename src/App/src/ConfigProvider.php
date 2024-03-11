<?php

declare(strict_types=1);

namespace App;

use Laminas\View\Model\ModelInterface;
use Mezzio\LaminasView\LaminasViewRenderer;

/**
 * The configuration provider for the App module
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
    public function __invoke(): array
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
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                ModelInterface::class          => Service\LayoutFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'defaultParams' => [
                'siteName' => 'FreeLancersRus',
            ],
            /**
             * about, clients, contact, cta, faq, portfolio, pricing, services, skills, team, why
             */
            'enabledPages' => [
                'about', 'services', 'faq', 'skills', 'contact',
            ],
            'paths' => [
                'app'     => [__DIR__ . '/../templates/app'],
                'error'   => [__DIR__ . '/../templates/error'],
                'layout'  => [__DIR__ . '/../templates/layout'],
                'page'    => [__DIR__ . '/../templates/page'],
                'partial' => [__DIR__ . '/../templates/partial'],
            ],
            'settings' => [
                'multiPage' => false,
                'enableNewsletter' => false,
                'enableFooterLinks' => false,
                'enableDropDownMenu' => false,
                'enableFooterContactInfo' => true,
            ],
        ];
    }
}
