<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Storage\PageRepository;
use App\Storage\SettingsRepository;
use Psr\Container\ContainerInterface;

class ContextMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ContextMiddleware
    {
        return new ContextMiddleware(
            $container->get(PageRepository::class),
            $container->get(SettingsRepository::class)
        );
    }
}
