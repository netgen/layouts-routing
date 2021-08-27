<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\API;

use Netgen\Bundle\LayoutsRoutingBundle\API\Value\DomainContent;
use Symfony\Component\HttpFoundation\Request;

interface ContentBuilderInterface
{
    public function accept(array $defaults, Request $request): bool;
    public function build(array $defaults, Request $request): DomainContent;
}
