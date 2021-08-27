<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin\Route;

use Netgen\Bundle\LayoutsRoutingBundle\Repository\RouteRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends Controller
{
    private RouteRepository $routeRepository;

    public function __construct(RouteRepository $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function __invoke(Request $request): Response
    {
        $list = $this->routeRepository->list();
        $page = $request->query->get('page', 1);

        $adapter = new ArrayAdapter($list);

        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(10)->setCurrentPage($page);

        return $this->render(
            '@NetgenLayoutsRouting/ngadminui/routes/list.html.twig',
            [
                'routes' => $pager,
            ]
        );
    }
}
