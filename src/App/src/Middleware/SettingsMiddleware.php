<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SettingsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private array $settings,
        private array $data
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        foreach ($this->settings as $setting => $value) {
            $request = $request->withAttribute($setting, $value);
        }
        foreach ($this->data as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }
        return $handler->handle($request);
    }
}
