<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\SiteAccessAware;
use eZ\Publish\Core\MVC\Symfony\SiteAccess\URILexer;
use Symfony\Cmf\Component\Routing\ProviderBasedGenerator;
use Symfony\Cmf\Component\Routing\VersatileGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Handles adding and removing siteaccess prefix when generating and matching a
 * route.
 */
class UrlGenerator implements UrlGeneratorInterface, SiteAccessAware, VersatileGeneratorInterface
{
    private ProviderBasedGenerator $innerGenerator;
    private ?SiteAccess $siteAccess;

    public function __construct(ProviderBasedGenerator $innerGenerator)
    {
        $this->innerGenerator = $innerGenerator;
    }

    public function setContext(RequestContext $context): void
    {
        $this->innerGenerator->setContext($context);
    }

    public function getContext(): RequestContext
    {
        return $this->innerGenerator->getContext();
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        return $this->prependSiteAccessIfNeeded(
            $this->innerGenerator->generate($name, $parameters, $referenceType)
        );
    }

    public function setSiteAccess(SiteAccess $siteAccess = null): void
    {
        $this->siteAccess = $siteAccess;
    }

    private function prependSiteAccessIfNeeded(string $url): string
    {
        if ($this->siteAccess === null) {
            return $url;
        }

        $matcher = $this->siteAccess->matcher;

        if ($matcher instanceof URILexer) {
            return $matcher->analyseLink($url);
        }

        return $url;
    }

    public function supports($name): bool
    {
        return $this->innerGenerator->supports($name);
    }

    public function getRouteDebugMessage($name, array $parameters = [])
    {
        return $this->innerGenerator->getRouteDebugMessage($name, $parameters);
    }
}
