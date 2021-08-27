<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return $this->render('@NetgenLayoutsRouting/ngadminui/dashboard.html.twig');
    }
}
