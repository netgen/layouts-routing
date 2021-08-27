<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\MenuPlugin;

use Netgen\Bundle\AdminUIBundle\MenuPlugin\MenuPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class RoutingAdmin implements MenuPluginInterface
{
    public function getIdentifier(): string
    {
        return 'nglayouts_routing_admin';
    }

    public function getTemplates(): array
    {
        return [
            'aside' => '@NetgenLayoutsRouting/ngadminui/menu_plugin/routing_plugin/aside.html.twig',
            'left' => '@NetgenLayoutsRouting/ngadminui/menu_plugin/routing_plugin/left.html.twig',
        ];
    }

    public function isActive(): bool
    {
        return true;
    }

    public function matches(Request $request): bool
    {
        return 0 === mb_stripos($request->attributes->get('_route'), 'nglayouts_routing_admin');
    }
}
