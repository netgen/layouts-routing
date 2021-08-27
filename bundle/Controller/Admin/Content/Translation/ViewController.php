<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin\Content\Translation;

use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    private ContentRepository $repository;

    public function __construct(ContentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request, int $id): Response
    {
        $content = $this->repository->findById(sprintf('%s:%s', Content::class, $id));

        return $this->render(
            '@NetgenLayoutsRouting/ngadminui/content/view.html.twig',
            [
                'content' => $content,
            ]
        );
    }
}
