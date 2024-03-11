<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ModelInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    public function __construct(
        private ModelInterface $layout,
        private ?TemplateRendererInterface $renderer = null
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [];

        return new HtmlResponse(
            $this->renderer->render(
                'app::home-page',
                ['layout' => $this->layout]
            )
        );
    }
}
