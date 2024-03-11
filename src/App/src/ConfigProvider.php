<?php

declare(strict_types=1);

namespace App;

use Laminas\View\Model\ModelInterface;

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
            'dependencies'       => $this->getDependencies(),
            'templates'          => $this->getTemplates(),
            /**
             * This key is the key you will find targeted in the doctype helper factory.
             * It can be added to any ConfigProvider to aggregate configuration to the view helpers
             */
            'view_helper_config' => $this->getViewHelperConfig(),
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
                // This is most likely the most important Factory in the application currently
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
                // This is used anywhere the siteName template property is called
                'siteName' => 'FreeLancersRus',
            ],
            /**
             * These are rendered as they are listed here.
             * about, clients, contact, cta, faq, portfolio, pricing, services, skills, team, why
             */
            'enabledPages' => [
                'about', 'services', 'faq', 'skills', 'contact',
            ],
            'paths' => [
                /**
                 * these are "namespaced", the key is the "namespace" in the form of 'app::templateName'.
                 * Notice you do not pass the extension ie .phtml
                 * See Handlers for usage example
                 */
                'app'     => [__DIR__ . '/../templates/app'],
                'error'   => [__DIR__ . '/../templates/error'],
                'layout'  => [__DIR__ . '/../templates/layout'],
                'page'    => [__DIR__ . '/../templates/page'],
                'partial' => [__DIR__ . '/../templates/partial'],
            ],
            // bool true|false
            'settings' => [
                'multiPage'               => false,
                'enableNewsletter'        => false,
                'enableFooterLinks'       => false,
                'enableDropDownMenu'      => false,
                'enableFooterContactInfo' => true,
            ],
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [
            'doctype' => 'HTML5', // This is what allows the doctype helper to work in the layout
        ];
    }
}
