<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ViewModel;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    private array $settings;
    private array $data;
    private string $homePage = 'app::home-page';
    private string $layout   = 'layout::default';

    public function __construct(
        private TemplateRendererInterface $template,
        private array $config
    ) {
        $this->settings = $config['settings'];
        $this->data     = $config['data'];
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->template->addDefaultParam(
            $this->homePage,
            'showOnHome',
            $this->settings['showOnHome']
        );

        if (isset($this->data['hero'])) {
            $hero = new ViewModel();
            $hero->setTemplate('partial::hero');
            $hero->setVariables($this->data['hero']);
            $this->template->addDefaultParam(
                $this->layout,
                'hero',
                $hero
            );
        }

        if (count($this->settings['showOnHome']) > 0) {
            // reset this for single page mode
            $path  = $this->config['templates']['paths']['page'][0];
            $files = glob($path . '/*.phtml');
            if (count($files) >= 1) {
                foreach ($files as $file) {
                    $template = basename($file, '.phtml');
                    if (in_array($template, $this->settings['showOnHome'])) {
                        $child         = new ViewModel();
                        $child->setTemplate('page::' . $template);

                        if (isset($this->data[$template])) {
                            // This trickery allows us to have a ['settings'][$template] and automatically inject them
                            $child->setVariables($this->data[$template]);
                        }
                        $this->template->addDefaultParam(
                            $this->homePage,
                            $template,
                            $child
                        );
                    }
                }
            }
        }

        return new HtmlResponse(
            $this->template->render(
                $this->homePage,
            )
        );
    }
}
