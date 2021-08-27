<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\AutoRoute;

class RouteRepository
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return \Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\AutoRoute[]
     */
    public function list(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\AutoRoute[]
     */
    public function findByContentId(int $id): array
    {
        return $this->getRepository()->findBy(['canonicalName' => 'nglayouts_routing_content_' . $id]);
    }

    private function getRepository(): ObjectRepository
    {
        return $this->getObjectManager()->getRepository(AutoRoute::class);
    }

    private function getObjectManager(): ObjectManager
    {
        return $this->managerRegistry->getManager();
    }
}
