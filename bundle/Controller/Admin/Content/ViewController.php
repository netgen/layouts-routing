<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin\Content;

use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;
use Netgen\Bundle\LayoutsRoutingBundle\Repository\RouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    private ContentRepository $contentRepository;
    private RouteRepository $routeRepository;

    public function __construct(ContentRepository $contentRepository, RouteRepository $routeRepository)
    {
        $this->contentRepository = $contentRepository;
        $this->routeRepository = $routeRepository;
    }

    public function __invoke(Request $request, int $id): Response
    {
        /** @var \Netgen\Bundle\LayoutsRoutingBundle\Entity\Content $content */
        $content = $this->contentRepository->findById(sprintf('%s:%s', Content::class, $id));
        $routes = $this->routeRepository->findByContentId($content->getId());

        return $this->render(
            '@NetgenLayoutsRouting/ngadminui/content/view.html.twig',
            [
                'content' => $content,
                'routes' => $routes,
            ]
        );
    }
}
