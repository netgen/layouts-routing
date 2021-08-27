<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Controller\Admin\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    public function __invoke(Request $request, int $id): Response
    {
        return $this->render('@NetgenLayoutsRouting/ngadminui/routes/view.html.twig');
    }
}
