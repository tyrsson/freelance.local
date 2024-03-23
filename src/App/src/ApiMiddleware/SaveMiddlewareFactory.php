<?php

declare(strict_types=1);

namespace App\ApiMiddleware;

use Psr\Container\ContainerInterface;

class SaveMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SaveMiddleware
    {
        return new SaveMiddleware();
    }
}
