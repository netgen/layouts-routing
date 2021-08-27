<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Core\ContentBuilder;

use Netgen\Bundle\LayoutsRoutingBundle\API\ContentBuilderInterface;
use Netgen\Bundle\LayoutsRoutingBundle\API\Value\DomainContent;
use Netgen\Bundle\LayoutsRoutingBundle\Core\Value\ConcreteDomainContent as ConcreteDomainContentValue;
use RuntimeException;

/**
 * @todo dispatch on Content::$type
 */
class ConcreteDomainContent implements ContentBuilderInterface
{
    public function accept($defaults, $request): bool
    {
        return true;
    }

    public function build($defaults, $request): DomainContent
    {
        /** @var \Netgen\Bundle\LayoutsRoutingBundle\Entity\Content $content */
        $content = $defaults['_content'];
        /** @var string $locale */
        $locale = $defaults['_locale'];
        /** @var \Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\AutoRoute $route */
        $route = $defaults['_route_object'];

        /** @var \Netgen\Bundle\LayoutsRoutingBundle\Entity\ContentTranslation $translation */
        foreach ($content->getTranslations() as $translation) {
            if ($translation->getLocale() === $locale) {
                return new ConcreteDomainContentValue($content, $translation, $route, 'concrete_domain_content');
            }
        }

        throw new RuntimeException('Could not find translation for the given route');
    }
}
