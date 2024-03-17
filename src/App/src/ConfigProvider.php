<?php

declare(strict_types=1);

namespace App;

use Laminas\Filter;
use Laminas\Validator;
use Laminas\View\Model\ModelInterface;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;

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
            'authentication' => $this->getAuthenticationConfig(),
            'dependencies'   => $this->getDependencies(),
            'templates'      => $this->getTemplates(),
            /**
             * This key is the key you will find targeted in the doctype helper factory.
             * It can be added to any ConfigProvider to aggregate configuration to the view helpers
             */
            'view_helper_config' => $this->getViewHelperConfig(),
            /**
             * This key is the one that is required by the Abstract filter factory
             */
            'input_filter_specs' => $this->getInputFilterSpecs(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            'redirect' => '/login',
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => UserRepository\PhpArray::class,
            ],
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                // This is most likely the most important Factory in the application currently
                ModelInterface::class          => Service\LayoutFactory::class,
                Middleware\AjaxRequestMiddleware::class => Middleware\AjaxRequestMiddlewareFactory::class,
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
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [
            'doctype' => 'HTML5', // This is what allows the doctype helper to work in the layout
        ];
    }

    public function getInputFilterSpecs(): array
    {
        /**
         * This is the "spec" for creating the InputFilter. If you are reading critically
         * your first question really should be... If this is an InputFilter why is it handling
         * validation as well... Well, honestly I cant answer that, but its very nice that it does.
         * Basically, each of the following arrays will map to the form elements in the contact form.
         * We have fields (elements for name, email, subject, message). So in the spec 'name' points to the
         * field/element name. This is so when we call setData on the InputFilter and pass it the posted
         * data it can bind each spec to its "input". As you can see each filter and validator can accept
         * its own config etc by passing it in the correct way in the spec. Its also important to note
         * that filters run in the order in which they are attached. You will have to look up which
         * filters/validators support which options.
         */
        return [
            // This key is what is used to pull the InputFilter from the plugin manager
            'contact' => [
                /**
                 * Start the spec for the "name" element in the <form action="" class=""></form>
                 */
                [
                    'name'     => 'name',
                    'required' => true,
                    'filters'  => [
                        ['name' => Filter\StripTags::class],
                        ['name' => Filter\StringTrim::class],
                    ],
                ],
                // end name
                // start email
                [
                    'name'     => 'email',
                    'required' => true,
                    'filters'  => [
                        ['name' => Filter\StripTags::class],
                        ['name' => Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name'    => Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min'      => 1,
                                'max'      => 320, // true, we may never see an email this length, but they are still valid
                            ],
                        ],
                        // @see EmailAddress for $options
                        ['name' => Validator\EmailAddress::class],
                    ],
                ],
                [
                    'name'     => 'subject',
                    'required' => true,
                    'filters'  => [
                        ['name' => Filter\StripTags::class],
                        ['name' => Filter\StringTrim::class],
                    ],
                ],
                [
                    'name'     => 'message',
                    'required' => true,
                    'filters'  => [
                        ['name' => Filter\StripTags::class],
                        ['name' => Filter\StringTrim::class],
                    ],
                ],
            ],
        ];
    }
}
