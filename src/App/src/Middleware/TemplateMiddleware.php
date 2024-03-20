<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\View\Model\ViewModel;
use Mezzio\Authentication\UserInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Session\LazySession;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateMiddleware implements MiddlewareInterface
{
    /** @var callable $factory */
    private $factory;

    private string $layout = 'layout::default';

    public function __construct(
        private TemplateRendererInterface $template,
        callable $factory,
        private array $settings,
        private array $data
    ) {
        $this->factory = $factory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class, null);
        $routeName   = $routeResult?->getMatchedRouteName();
        $isHome      = $routeName === 'home' ? true : false;

        /** @var LazySession */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $user    = $session->get(UserInterface::class);
        // If we have a user then assign it to all templates, this does not assign it to partials
        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'user',
            $user
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'isHome',
            $isHome
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'currentRoute',
            $routeName
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'siteName',
            $this->settings['siteName']
        );

        // create the nav model
        $nav = new ViewModel();
        $nav->setTemplate('partial::nav');
        $nav->setVariables(
            [
                'isHome'             => $isHome,
                'activeLinks'        => $this->settings['showInMenu'] + $this->settings['showOnHome'],
                'enableDropDownMenu' => $this->settings['enableDropDownMenu'],
                'showOnHome'         => $this->settings['showOnHome'],
                'currentRoute'       => $routeName,
                'user'               => $user,
            ]
        );
        // assign it to the layout since its global
        $this->template->addDefaultParam(
            $this->layout,
            'nav',
            $nav
        );

        // create the footer model
        $footer = new ViewModel();
        $footer->setTemplate('partial::footer');
        $footerVars = array_merge($this->data['footer'], $this->data['contact']);
        $footer->setVariables($footerVars);
        $footer->setVariable('siteName', $this->settings['siteName']);
        $this->template->addDefaultParam(
            $this->layout,
            'footer',
            $footer
        );
        return $handler->handle($request);
    }
}
