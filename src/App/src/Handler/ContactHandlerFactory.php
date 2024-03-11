<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ContactHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ContactHandler
    {
        return new ContactHandler($container->get(TemplateRendererInterface::class));
    }
}
