<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\TextResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContactHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /**
         * Please note
         *
         * General:
         * By the time execution gets here we have either already sent the email
         * since validation passed or we passed in the error messages from the
         * validators so that they can be passed through to the template file.
         * I handled it that way to keep the Handler very light and so it
         * only has to handle the response. The ContactMiddleware handles
         * the heavy lifting of sending the email and performing validation
         *
         * On validation failure:
         * We passed the validator errors via the request so that we can pass them to
         * a .phtml file for rendering and we send it back in the response
         * The AjaxMiddleware handles disabling the layout for the response
         *
         * On Validation success:
         * We send a text response of OK since that is what the client side js is looking for
         */
        if (! $request->getAttribute('emailSent', false)) {
            return new TextResponse(
                $this->renderer->render(
                    'partial::formErrors',
                    [
                        'errors' => $request->getAttribute('formError')
                    ],
                ),
            );
        }
        return new TextResponse(
            'OK'
        );
    }
}
