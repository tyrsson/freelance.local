<?php

declare(strict_types=1);

namespace UserManager;

use Axleus\Constants;
use Laminas\I18n\Translator\Loader\PhpArray;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use UserManager\Form;

/**
 * The configuration provider for the UserManager module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    public string $baseDir = __DIR__ . '/../language';

    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'authentication'      => $this->getAuthConfig(),
            'dependencies'        => $this->getDependencies(),
            'form_elements'       => $this->getFormElementConfig(),
            'middleware_pipeline' => $this->getPipelineConfig(),
            'templates'           => $this->getTemplates(),
            'routes'              => $this->getRoutes(),
            'tactician'           => $this->getCommandConfig(),
            'translator'          => $this->getTranslatorConfig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases'   => [
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => Storage\UserRepository::class,
            ],
            'factories' => [
                Auth\LoginCommandHandler::class      => Auth\LoginCommandHandlerFactory::class,
                Auth\CurrentUser::class              => Auth\CurrentUserFactory::class,
                Handler\LoginHandler::class          => Handler\LoginHandlerFactory::class,
                Handler\LogoutHandler::class         => Handler\LogoutHandlerFactory::class,
                Handler\ProfileHandler::class        => Handler\ProfileHandlerFactory::class,
                Middleware\IdentityMiddleware::class => Middleware\IdentityMiddlewareFactory::class,
                Storage\UserRepository::class        => Storage\UserRepositoryFactory::class,
                UserInterface::class                 => Auth\CurrentUserFactory::class,
            ],
            'invokables' => [
                Auth\LogoutCommandHandler::class => Auth\LogoutCommandHandler::class,
            ],
        ];
    }

    public function getPipelineConfig(): array
    {
        return [
            [
                'middleware' => Middleware\IdentityMiddleware::class,
                'priority' => Constants::PIPE_PRIORITIES[Middleware\IdentityMiddleware::class] // todo: determine best priority for this
            ],
        ];
    }

    public function getAuthConfig(): array
    {
        return [
            'username' => 'userName',
            'password' => 'password',
            'details'  => ['id', 'email', 'firstName', 'lastName', 'birthday'],
            'redirect' => '/user/login',
        ];
    }

    public function getRoutes(): array
    {
        return [
            [
                'path'            => '/user/login',
                'name'            => 'user.login',
                'middleware'      => [
                    Handler\LoginHandler::class,
                ],
                'allowed_methods' => ['GET', 'POST'],
            ],
            [
                'path'            => '/user/profile[/{userName:[a-zA-Z]+}]',
                'name'            => 'user.profile',
                'middleware'      => [
                    AuthenticationMiddleware::class,
                    Handler\ProfileHandler::class,
                ],
                'allowed_methods' => ['GET'],
            ],
            [
                'path'            => '/user/logout',
                'name'            => 'user.logout',
                'middleware'      => [
                    AuthenticationMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
                'allowed_methods' => ['GET'],
            ],
        ];
    }

    public function getFormElementConfig(): array
    {
        return [
            'factories' => [
                Form\Login::class => Form\LoginFactory::class,
            ],
        ];
    }

    public function getCommandConfig(): array
    {
        return [
            'handler-map' => [
                Auth\LoginCommand::class  => Auth\LoginCommandHandler::class,
                Auth\LogoutCommand::class => Auth\LogoutCommandHandler::class,
            ],
        ];
    }

    public function getTemplates(): array
    {
        return [
            'paths' => [
                'user-manager' => [__DIR__ . '/../templates/'],
            ],
        ];
    }

    public function getTranslatorConfig(): array
    {
        return [
            'translation_file_patterns' => [ // This is the only config that is needed for 1 translation per file
                [
                    'type'     => PhpArray::class,
                    'filename' => 'en_US.php',
                    'base_dir' => __DIR__ . '/../language',
                    'pattern'  => '%s.php',
                ],
            ],
            'translation_files' => [
                [
                    'type'        => PhpArray::class,
                    'filename'    => __DIR__ . '/../language/en_US.php',
                    'locale'      => 'en_US',
                    'text_domain' => 'default',
                ],
            ],
        ];
    }
}
