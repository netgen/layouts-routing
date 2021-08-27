<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;

class ContentRepository
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return \Netgen\Bundle\LayoutsRoutingBundle\Entity\Content[]
     */
    public function list(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findByRemoteId(string $remoteId): ?Content
    {
        return $this->getRepository()->findOneBy(['remoteId' => $remoteId]);
    }

    private function getRepository(): ObjectRepository
    {
        return $this->getObjectManager()->getRepository(Content::class);
    }

    private function getObjectManager(): ObjectManager
    {
        return $this->managerRegistry->getManager();
    }
}
