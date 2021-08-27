<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle;

use Netgen\Bundle\LayoutsRoutingBundle\DependencyInjection\Compiler\ContentBuilderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NetgenLayoutsRoutingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ContentBuilderPass());
    }
}
