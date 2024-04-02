<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Form\Login;
use App\Storage\PageRepository;
use App\Storage\PartialRepository;
use Laminas\Form\FormElementManager;
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
        private FormElementManager $formManager,
        private PageRepository $pageRepo,
        private PartialRepository $partialRepo,
        callable $factory
    ) {
        $this->factory = $factory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class, null);
        $routeName   = $routeResult?->getMatchedRouteName();
        $isHome      = $routeName === 'home' ? true : false;
        $settings    = $request->getAttribute('settings', null);

        // we need arrays here
        $showOnHome = ($request->getAttribute('showOnHome'));
        $showInMenu = $request->getAttribute('showInMenu');

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
            $settings?->siteName
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'enableLogin',
            $settings?->enableLogin
        );

        $this->template->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'enableLoginModal',
            $settings?->enableLoginModal
        );

        if ($settings?->enableLoginModal && $settings?->enableLogin) {
            $loginModal = new ViewModel();
            $loginModal->setTemplate('partial::login-modal');
            $loginModal->setVariable('form', $this->formManager->get(Login::class));
            $this->template->addDefaultParam(
                $this->layout,
                'loginModal',
                $loginModal
            );
        }

        // create the nav model
        $nav = new ViewModel();
        $nav->setTemplate('partial::nav');
        $nav->setVariables(
            [
                'isHome'       => $isHome,
                'activeLinks'  => $showInMenu + $showOnHome,
                'showOnHome'   => $showOnHome,
                'currentRoute' => $routeName,
                'user'         => $user,
                'settings'     => $settings,
            ]
        );
        // assign it to the layout since its global
        $this->template->addDefaultParam(
            $this->layout,
            'nav',
            $nav
        );

        // todo: fix newsletter rendering in footer, currently broken. Maybe add it as child to footer once we get page settings returned
        // $newsletterData = $this->pageRepo->findOneByTitle('newsletter');
        // $newsletter = new ViewModel();
        // $newsletter->setTemplate($newsletterData->template);


        // footer WORKS!!!!!!!!!! Follow pattern

        // create the footer model
        $footerData = $this->partialRepo->findOneBySectionId('#footer');
        $footer = new ViewModel();
        $footer->setTemplate($footerData->template);
        //$footerVars = array_merge($this->data['footer']);
        //$footer->setVariables($footerVars);
        $footer->setVariable('siteName', $settings?->siteName);
        $this->template->addDefaultParam(
            $this->layout,
            'footer',
            $footer
        );
        return $handler->handle($request);
    }
}
