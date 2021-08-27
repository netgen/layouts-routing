<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Core\ContentBuilder;

use Netgen\Bundle\LayoutsRoutingBundle\API\ContentBuilderInterface;
use Netgen\Bundle\LayoutsRoutingBundle\API\Value\DomainContent;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class Aggregate implements ContentBuilderInterface
{
    /**
     * @var \Netgen\Bundle\LayoutsRoutingBundle\API\ContentBuilderInterface[]
     */
    private array $builders;

    public function __construct(array $builders = [])
    {
        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }
    }

    public function addBuilder(ContentBuilderInterface $builder): void
    {
        $this->builders[] = $builder;
    }

    public function accept(array $defaults, Request $request): bool
    {
        return true;
    }

    public function build(array $defaults, Request $request): DomainContent
    {
        foreach ($this->builders as $builder) {
            if ($builder->accept($defaults, $request)) {
                return $builder->build($defaults, $request);
            }
        }

        throw new RuntimeException('Content type is not handled');
    }
}
