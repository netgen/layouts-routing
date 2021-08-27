<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin\Content;

use Netgen\Bundle\LayoutsRoutingBundle\Repository\ContentRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends Controller
{
    private ContentRepository $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function __invoke(Request $request): Response
    {
        $list = $this->contentRepository->list();
        $page = $request->query->get('page', 1);

        $adapter = new ArrayAdapter($list);

        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(10)->setCurrentPage($page);

        return $this->render(
            '@NetgenLayoutsRouting/ngadminui/content/list.html.twig',
            [
                'content_items' => $pager,
            ]
        );
    }
}
