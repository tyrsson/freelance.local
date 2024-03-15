<?php

declare(strict_types=1);

namespace App;

use Laminas\Filter;
use Laminas\Validator;
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
            /**
             * This key is the one that is required by the Abstract filter factory
             */
            'input_filter_specs' => $this->getInputFilterSpecs(),
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
                'multiPage'          => false,
                'enableDropDownMenu' => false,
                'contact' => [
                    'enableMap'    => true, // bool true|false
                    'location'     => null, // string|null
                    'email'        => 'info@example.com', // string|null
                    'phone'        => '+1 5589 55488 55s', // string|null
                    'contactBlurb' => 'This is your contact blurb.', // string|null
                ],
                'hero'    => [
                    'heroHeading'     => 'Better Solutions For Your Business', // string|null
                    'blurb'           => 'Hire us we\'re AWESOMESAUCE', // string|null
                    'enableHeroVideo' => false, // bool true|false
                    'heroVideoLink'   => 'https://www.youtube.com/watch?v=jDDaplaOz7Q', //string|null
                    'img'             => 'hero-img.png', // string|null
                ],
                'footer' => [
                    'enableFooterLinks'       => true, // bool true|false
                    'enableNewsletter'        => true, // bool true|false
                    'enableFooterContactInfo' => true, // bool true|false
                    'newsLetterHeading'       => 'Join Our Newsletter', // string|null
                    'newsLetterBlurb'         => 'Tamen quem nulla quae legam multos aute sint culpa legam noster magna', // string|null
                ],
                'faq' => [
                    'faqHeading' => 'FREQUENTLY ASKED QUESTIONS', // string|null
                    'faqBlurb'   => 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.', // string|null
                    'data' => [
                        [
                            'question' => 'Non consectetur a erat nam at lectus urna duis?',
                            'answer'   => 'Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.',
                        ],
                        [
                            'question' => 'Feugiat scelerisque varius morbi enim nunc?',
                            'answer'   => 'Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.',
                        ],
                        [
                            'question' => 'Dolor sit amet consectetur adipiscing elit?',
                            'answer'   => 'Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis',
                        ],
                        [
                            'question' => 'Tempus quam pellentesque nec nam aliquam sem et tortor consequat?',
                            'answer'   => 'Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in.',
                        ],
                        [
                            'question' => 'Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor?',
                            'answer'   => 'Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque.',
                        ],
                    ],
                ],
                'services' => [
                    'serviceHeading' => 'Services', // string|null
                    'serviceBlurb'   => 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.',
                    'data' => [
                        [
                            'icon'    => 'bx bxl-dribbble', // string|null
                            'name'    => 'Lorem Ipsum', // string|null
                            'link'    => null, // string|null
                            'content' => 'Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi',
                        ],
                        [
                            'icon'    => 'bx bx-file', // string|null
                            'name'    => 'Sed ut perspici', // string|null
                            'link'    => null, // string|null
                            'content' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore', //string|null
                        ],
                        [
                            'icon'    => 'bx bx-tachometer', // string|null
                            'name'    => 'Magni Dolores', // string|null
                            'link'    => null, // string|null
                            'content' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia', //string|null
                        ],
                        [
                            'icon'    => 'bx bx-layer', // string|null
                            'name'    => 'Nemo Enim', // string|null
                            'link'    => null, // string|null
                            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis', //string|null
                        ],
                    ],
                ],
                'skills' => [
                    'img'          => 'skills.png', // string|null
                    'skillHeading' => 'Our Skillz', // string|null
                    'skillBlurb'   => 'These are the skillz we use most.', // string|null
                    'data' => [
                        [
                            'name'       => 'HTML', // string|null
                            'percentage' => '100', // string|null without %
                        ],
                        [
                            'name'       => 'PHP', // string|null
                            'percentage' => '100', // string|null without %
                        ],
                        [
                            'name'       => 'CSS', // string|null
                            'percentage' => '90', // string|null without %
                        ],
                        [
                            'name'       => 'JAVASCRIPT', // string|null
                            'percentage' => '75', // string|null without %
                        ],
                    ],
                ],
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
