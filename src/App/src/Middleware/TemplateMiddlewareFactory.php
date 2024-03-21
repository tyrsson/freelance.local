<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Form\FormElementManager;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TemplateMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : TemplateMiddleware
    {
        return new TemplateMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get(FormElementManager::class),
            $container->get(UserInterface::class),
            $container->get('config')['settings'],
            $container->get('config')['data']
        );
    }
}
