<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use Symfony\Cmf\Component\Routing\NestedMatcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Overridden for siteaccess path info.
 */
class FinalMatcher extends UrlMatcher
{
    use PathInfoTrait;

    public function finalMatch(RouteCollection $collection, Request $request): array
    {
        $this->routes = $collection;
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->setContext($context);

        return $this->match($this->getSiteAccessPathInfo($request));
    }
}
