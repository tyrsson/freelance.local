<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class SettingsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SettingsMiddleware
    {
        return new SettingsMiddleware(
            $container->get('config')['settings'],
            $container->get('config')['data']
        );
    }
}
