<?php

declare(strict_types=1);

namespace App\Handler;

use App\Form\Login;
use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;
use Mezzio\Session\LazySession;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;

class LoginHandler implements RequestHandlerInterface
{
    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    public function __construct(
        private TemplateRendererInterface $template,
        private Login $form,
        private PhpSession $adapter
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (! $request->getAttribute('enableLogin', false)) {
            return new RedirectResponse('/');
        }
        /** @var LazySession */
        $session  = $request->getAttribute('session');
        $redirect = $this->getRedirect($request, $session);
        // Handle submitted credentials
        if (Http::METHOD_POST === $request->getMethod()) {
            // handle the login
            return $this->handleLogin($request, $session, $redirect);
        }
        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);

        // Render and return a response:
        return new HtmlResponse($this->template->render(
            'app::login',
            [
                'form' => $this->form
            ] // parameters to pass to template
        ));
    }

    private function handleLogin(
        ServerRequestInterface $request,
        SessionInterface $session,
        string $redirect
    ): ResponseInterface {
        // remove this before we attempt to authenticate since user session takes precendence
        $session->unset(UserInterface::class);

        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);

            // handle ajaxed login from modal
            if ($request->getAttribute('isAjax', false)) {
                return new JsonResponse(['message' => 'Login Successful']);
            } else {
                // non ajaxed redirect
                return new RedirectResponse($redirect);
            }
        } elseif ($request->getAttribute('isAjax', false)) {
            // ajaxed login failure, let them know
            return new JsonResponse(['message' => 'Login Failed'], 401);
        }

        return new HtmlResponse($this->template->render(
            'app::login',
            [
                'form' => $this->form,
                'error' => 'Invalid credentials please try again.'
            ] // parameters to pass to template
        ));
    }

    private function getRedirect(
        ServerRequestInterface $request,
        SessionInterface $session
    ): string {
        /** @var string */
        $redirect = $session->get(self::REDIRECT_ATTRIBUTE);

        if (! $redirect) {
            $uri      = new Uri($request->getHeaderLine('Referer'));
            $redirect = $uri->getPath();
        }
        return $redirect;
    }
}
