<?php

declare(strict_types=1);

namespace App\ApiHandler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class SaveHandlerFactory
{
    public function __invoke(ContainerInterface $container) : SaveHandler
    {
        return new SaveHandler($container->get(TemplateRendererInterface::class));
    }
}
