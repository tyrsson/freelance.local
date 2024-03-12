<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    /**
     * This sets up a middleware pipeline and runs the middleware
     * and handlers in the order in which they are registered ie, their order in the array.
     * Only get and post request are valid, anything else will prompt an error.
     * Since this pipeline is path segregated this execution is limited to
     * /contact none of these middleware/handlers will run for anyother path
     */
    $app->route(
        '/contact',
        [
            BodyParamsMiddleware::class,
            App\Middleware\AjaxRequestMiddleware::class,
            App\Middleware\ContactMiddleware::class,
            App\Handler\ContactHandler::class
        ],
        [
            'GET',
            'POST',
        ],
        'contact'
    );
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
};
