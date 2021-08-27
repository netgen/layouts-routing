<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing;

use Netgen\Bundle\LayoutsRoutingBundle\API\Value\DomainContent;
use RuntimeException;
use Symfony\Cmf\Component\Routing\ChainedRouterInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class DomainContentRouter implements ChainedRouterInterface, RequestMatcherInterface
{
    protected UrlGeneratorInterface $urlGenerator;
    protected RequestContext $requestContext;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function matchRequest(Request $request): array
    {
        throw new ResourceNotFoundException('ContentUrlAliasRouter does not support matching requests.');
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        if (!$name instanceof DomainContent) {
            throw new RouteNotFoundException('Could not match route');
        }

        return $this->urlGenerator->generate($name->getRoute(), $parameters, $referenceType);
    }

    public function getRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    public function setContext(RequestContext $context): void
    {
        $this->urlGenerator->setContext($context);
    }

    public function getContext(): RequestContext
    {
        return $this->urlGenerator->getContext();
    }

    public function match($pathinfo): array
    {
        throw new RuntimeException("The ContentRouter doesn't support the match() method.");
    }

    public function supports($name): bool
    {
        return $name instanceof DomainContent;
    }

    public function getRouteDebugMessage($name, array $parameters = [])
    {
        if ($name instanceof RouteObjectInterface) {
            return 'Route with key ' . $name->getRouteKey();
        }

        if ($name instanceof SymfonyRoute) {
            return 'Route with pattern ' . $name->getPath();
        }

        return $name;
    }
}
