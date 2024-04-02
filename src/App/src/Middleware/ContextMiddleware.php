<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Storage\PageRepository;
use App\Storage\SettingsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContextMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PageRepository $pageRepo,
        private SettingsRepository $settingsRepo
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $settings = $this->settingsRepo->fetchContext();
        $request  = $request->withAttribute('settings', $settings);
        $pages    = $this->pageRepo->findAll();
        $request  = $request->withAttribute('pages', $pages);
        $request  = $request->withAttribute(
            'showOnHome',
            $this->pageRepo->findAttachedPages(returnArray: true)
        );
        $request  = $request->withAttribute(
            'showInMenu',
            $this->pageRepo->findMenu(returnArray: true)
        );
        return $handler->handle($request);
    }
}
