<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\API;

use Netgen\Bundle\LayoutsRoutingBundle\API\Value\ContentSyncStruct;
use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;

interface ContentSyncerInterface
{
    public function sync(ContentSyncStruct $struct): Content;

    /**
     * @param \Netgen\Bundle\LayoutsRoutingBundle\API\Value\ContentSyncStruct[] $structs
     *
     * @return \Netgen\Bundle\LayoutsRoutingBundle\Entity\Content[]
     */
    public function syncMultiple(array $structs): array;

    public function remove(Content $content): void;

    public function removeOrphanedRoutes(): void;
}
