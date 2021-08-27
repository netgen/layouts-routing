<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing\SiteAccess;

use Symfony\Cmf\Component\Routing\Candidates\Candidates as BaseCandidates;
use Symfony\Component\HttpFoundation\Request;

/**
 * Overridden for siteaccess path info.
 */
class Candidates extends BaseCandidates
{
    use PathInfoTrait;

    public function getCandidates(Request $request): array
    {
        $url = $this->getSiteAccessPathInfo($request);
        $candidates = $this->getCandidatesFor($url);
        $locale = $this->determineLocale($url);

        if ($locale) {
            $candidates = array_unique(
                array_merge(
                    $candidates,
                    $this->getCandidatesFor(substr($url, strlen($locale) + 1))
                )
            );
        }

        return $candidates;
    }
}
