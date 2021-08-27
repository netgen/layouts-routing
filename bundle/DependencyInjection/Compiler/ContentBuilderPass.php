<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContentBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('nglayouts_routing.content_builder.aggregate') ) {
            return;
        }

        $aggregateBuilderDefinition = $container->getDefinition('nglayouts_routing.content_builder.aggregate');
        $builders = $container->findTaggedServiceIds('nglayouts_routing.content_builder');

        foreach ($builders as $id => $attributes) {
            $aggregateBuilderDefinition->addMethodCall('addBuilder', [new Reference($id)]);
        }
    }
}
