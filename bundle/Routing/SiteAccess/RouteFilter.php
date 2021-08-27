<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\Locale\LocaleConverter;
use Symfony\Cmf\Component\Routing\NestedMatcher\RouteFilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * This Route filter checks siteaccess prioritized language list to filter out
 * all routes except a single one that should be rendered on the website.
 */
class RouteFilter implements RouteFilterInterface
{
    private LocaleConverter $localeConverter;
    private ConfigResolverInterface $configResolver;

    public function __construct(LocaleConverter $localeConverter, ConfigResolverInterface $configResolver)
    {
        $this->localeConverter = $localeConverter;
        $this->configResolver = $configResolver;
    }

    public function filter(RouteCollection $collection, Request $request): RouteCollection
    {
        $filteredCollection = new RouteCollection();
        $prioritizedLanguageList = $this->configResolver->getParameter('languages');

        foreach ($prioritizedLanguageList as $languageCode) {
            foreach ($collection->all() as $name => $route) {
                if ($this->routeMatchesLanguage($route, $languageCode)) {
                    $filteredCollection->add($name, $route);

                    return $filteredCollection;
                }
            }
        }

        return $filteredCollection;
    }

    private function routeMatchesLanguage(Route $route, string $languageCode): bool
    {
        $routeLanguageCode = $this->getRouteLanguageCode($route);

        return $routeLanguageCode === $languageCode;
    }

    private function getRouteLanguageCode(Route $route): string
    {
        return $this->localeConverter->convertToEz(
            $route->getRequirement('_locale')
        );
    }
}
