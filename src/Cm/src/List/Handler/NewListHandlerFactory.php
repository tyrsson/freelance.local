<?php

declare(strict_types=1);

namespace Cm\List\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class NewListHandlerFactory
{
    public function __invoke(ContainerInterface $container) : NewListHandler
    {
        return new NewListHandler($container->get(TemplateRendererInterface::class));
    }
}
