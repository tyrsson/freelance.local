<?php

declare(strict_types=1);

namespace App\Handler;

use League\Tactician\CommandBus;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container): LogoutHandler
    {
        /** @var LaminasViewRenderer */
        $renderer = $container->get(TemplateRendererInterface::class);
        return new LogoutHandler(
            $renderer
        );
    }
}
