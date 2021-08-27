<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter as BaseDynamicRouter;
use Symfony\Cmf\Component\Routing\Event\Events;
use Symfony\Cmf\Component\Routing\Event\RouterMatchEvent;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Overridden for siteaccess path info.
 */
class DynamicRouter extends BaseDynamicRouter
{
    use PathInfoTrait;

    private BaseDynamicRouter $innerRouter;

    public function __construct(
        BaseDynamicRouter $innerRouter,
        RequestContext $context,
        $matcher,
        UrlGeneratorInterface $generator,
        $uriFilterRegexp = '',
        EventDispatcherInterface $eventDispatcher = null,
        RouteProviderInterface $provider = null
    ) {
        parent::__construct($context, $matcher, $generator, $uriFilterRegexp, $eventDispatcher, $provider);

        $this->innerRouter = $innerRouter;
    }

    public function generate($name, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->innerRouter->generate($name, $parameters, $referenceType);
    }

    public function match($url): array
    {
        return $this->innerRouter->match($url);
    }

    public function matchRequest(Request $request): array
    {
        if ($this->eventDispatcher) {
            $event = new RouterMatchEvent($request);
            $this->eventDispatcher->dispatch(Events::PRE_DYNAMIC_MATCH_REQUEST, $event);
        }

        $pathInfo = $this->getSiteAccessPathInfo($request);

        if ($this->uriFilterRegexp && !preg_match($this->uriFilterRegexp, $pathInfo)) {
            throw new ResourceNotFoundException(
                "{$pathInfo} does not match the '{$this->uriFilterRegexp}' pattern"
            );
        }

        $matcher = $this->getMatcher();

        if ($matcher instanceof UrlMatcherInterface) {
            $defaults = $matcher->match($pathInfo);
        } else {
            $defaults = $matcher->matchRequest($request);
        }

        $defaults = $this->applyRouteEnhancers($defaults, $request);

        return $this->cleanDefaults($defaults, $request);
    }

    protected function applyRouteEnhancers($defaults, Request $request): array
    {
        foreach ($this->innerRouter->getRouteEnhancers() as $enhancer) {
            $defaults = $enhancer->enhance($defaults, $request);
        }

        return $defaults;
    }
}
