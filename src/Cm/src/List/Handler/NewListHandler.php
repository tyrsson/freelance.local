<?php

declare(strict_types=1);

namespace Cm\List\Handler;

use Cm\List\Form\ListForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;

class NewListHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(
        TemplateRendererInterface $renderer,
        private ListForm $form
    ) {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Do some work...
        // Render and return a response:
        return new HtmlResponse($this->renderer->render(
            'cm::new-list',
            ['form' => $this->form] // parameters to pass to template
        ));
    }
}
