<?php

declare(strict_types=1);

namespace App\Middleware;

use Cm\Storage\PageRepository;
use Cm\Storage\PartialRepository;
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
            $container->get(PageRepository::class),
            $container->get(PartialRepository::class),
            $container->get(UserInterface::class)
        );
    }
}
