<?php

declare(strict_types=1);

namespace App\Handler;

use App\Storage\PageRepository;
use App\Storage\PartialRepository;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ViewModel;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandler implements RequestHandlerInterface
{
    private string $homePage = 'app::home-page';
    private string $layout   = 'layout::default';

    public function __construct(
        private TemplateRendererInterface $template,
        private PageRepository $pageRepo,
        private PartialRepository $partialRepo,
        private array $config
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $showOnHome = ($request->getAttribute('showOnHome'));
        $this->template->addDefaultParam(
            $this->homePage,
            'showOnHome',
            $showOnHome
        );

        $resultSet = $this->partialRepo->findAllBySectionId('hero');
        if ($resultSet->count() > 0) {
            $hero  = new ViewModel();
            $isSet = null;
            foreach ($resultSet as $row) {
                if ($isSet === null) {
                    $hero->setTemplate($row->template);
                    $isSet = true;
                }
                $hero->setVariable($row->variable, $row->value);
            }
            $this->template->addDefaultParam(
                $this->layout,
                'hero',
                $hero
            );
        }

        if (count($showOnHome) > 0) {
            // we are only hunting page template files here, partials are handled differently ;)
            $path  = $this->config['templates']['paths']['page'][0];
            $files = glob($path . '/*.phtml');
            $templates = [];
            foreach ($files as $file) {
                $templates[] = basename($file, '.phtml');
            }

            foreach ($showOnHome as $attached) {
                if ($attached['showOnHome'] && in_array($attached['sectionId'], $templates)) {
                    $child = new ViewModel();
                    $child->setTemplate($attached['template']);
                    $child->setVariables($attached);
                    $this->template->addDefaultParam(
                        $this->homePage,
                        $attached['sectionId'],
                        $child
                    );
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
