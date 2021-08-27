<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use Symfony\Component\HttpFoundation\Request;

/**
 * Extracts siteaccess path info from the request.
 */
trait PathInfoTrait
{
    protected function getSiteAccessPathInfo(Request $request): string
    {
        return $request->attributes->get('semanticPathinfo', $request->getPathInfo());
    }
}
