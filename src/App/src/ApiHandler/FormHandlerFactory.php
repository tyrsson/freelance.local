<?php

declare(strict_types=1);

namespace App\ApiHandler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class FormHandlerFactory
{
    public function __invoke(ContainerInterface $container) : FormHandler
    {
        return new FormHandler($container->get(TemplateRendererInterface::class));
    }
}
