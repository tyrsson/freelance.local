<?php

declare(strict_types=1);

namespace Cm\List\Handler;

use Cm\List\Form\ListForm;
use Laminas\Form\FormElementManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class NewListHandlerFactory
{
    public function __invoke(ContainerInterface $container) : NewListHandler
    {
        $fm = $container->get(FormElementManager::class);
        return new NewListHandler(
            $container->get(TemplateRendererInterface::class),
            $fm->get(ListForm::class)
        );
    }
}
