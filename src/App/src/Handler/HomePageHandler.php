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
    private array $data;
    private string $homePage = 'app::home-page';
    private string $layout   = 'layout::default';

    public function __construct(
        private TemplateRendererInterface $template,
        private PageRepository $pageRepo,
        private PartialRepository $partialRepo,
        private array $config
    ) {
        $this->data = $config['data'];
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $showOnHome = ($request->getAttribute('showOnHome'));
        $this->template->addDefaultParam(
            $this->homePage,
            'showOnHome',
            $showOnHome
        );

        $resultSet = $this->partialRepo->findAllBySectionId('#hero');
        if ($resultSet->count() > 0) {
            $hero = new ViewModel();
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
            // reset this for single page mode
            $path  = $this->config['templates']['paths']['page'][0];
            $files = glob($path . '/*.phtml');
            if (count($files) >= 1) {
                foreach ($files as $file) {
                    $template = basename($file, '.phtml');
                    if (in_array($template, $showOnHome)) {
                        $child         = new ViewModel();
                        $child->setTemplate('page::' . $template);
                        // todo remove $this->data usage
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
