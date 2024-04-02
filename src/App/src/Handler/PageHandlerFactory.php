<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class PageHandlerFactory
{
    public function __invoke(ContainerInterface $container) : PageHandler
    {
        return new PageHandler($container->get(TemplateRendererInterface::class));
    }
}
