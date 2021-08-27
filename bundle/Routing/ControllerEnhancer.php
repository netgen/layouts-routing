<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing;

use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @todo set controller from the view configuration
 */
class ControllerEnhancer implements RouteEnhancerInterface
{
    private string $marker;

    public function __construct(string $marker)
    {
        $this->marker = $marker;
    }

    public function enhance(array $defaults, Request $request): array
    {
        if (array_key_exists('_controller', $defaults) && $defaults['_controller'] === $this->marker) {
            $defaults['_controller'] = 'nglayouts_routing.controller.view';
        }

        return $defaults;
    }
}
