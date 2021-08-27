<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing;

use Netgen\Bundle\LayoutsRoutingBundle\API\ContentBuilderInterface;
use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContentEnhancer implements RouteEnhancerInterface
{
    private ContentBuilderInterface $builder;

    public function __construct(ContentBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function enhance(array $defaults, Request $request): array
    {
        $defaults['_content'] = $this->builder->build($defaults, $request);

        return $defaults;
    }
}
