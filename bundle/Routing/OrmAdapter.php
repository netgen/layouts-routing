<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Adapter\OrmAdapter as BaseOrmAdapter;
use Symfony\Cmf\Component\RoutingAuto\Model\AutoRouteInterface;

class OrmAdapter extends BaseOrmAdapter
{
    private EntityManagerInterface $em;
    private string $autoRouteFqcn;
    private ObjectRepository $repository;

    /**
     * @throws \ReflectionException
     */
    public function __construct(EntityManagerInterface $em, string $autoRouteFqcn)
    {
        $reflection = new ReflectionClass($autoRouteFqcn);
        if (!$reflection->isSubclassOf(AutoRouteInterface::class)) {
            throw new InvalidArgumentException(sprintf('AutoRoute documents have to implement the AutoRouteInterface, "%s" does not.', $autoRouteFqcn));
        }

        parent::__construct($em, $autoRouteFqcn);

        $this->em = $em;
        $this->autoRouteFqcn = $autoRouteFqcn;
        $this->repository = $this->em->getRepository($this->autoRouteFqcn);
    }

    /**
     * @throws \JsonException
     * @todo override the entity, get rid of the JSON type and rewrite this cleanly
     */
    public function getRoutes($entity): array
    {
        $className = $this->getClassName($entity);
        $id = $this->em->getClassMetadata($className)->getIdentifierValues($entity);

        $rsm = $this->repository->createResultSetMappingBuilder('o');
        $rsm->addRootEntityFromClassMetadata($this->autoRouteFqcn, 'o');

        $routeMetadata = $this->em->getClassMetadata($this->autoRouteFqcn);

        //this workaround is needed to bypass doctrine escaping parameters
        $sql = sprintf(
            "select %s from %s o WHERE o.%s = '%s' and o.%s = cast('%s' as json) order by position",
            $rsm->generateSelectClause(),
            $routeMetadata->table['name'],
            'content_class',//AutoRoute::CONTENT_CLASS_KEY,
            addslashes($className),
            'content_id',//AutoRoute::CONTENT_ID_KEY,
            json_encode($id, JSON_THROW_ON_ERROR | JSON_HEX_QUOT)
        );

        return $this->em->createNativeQuery($sql, $rsm)->getResult();
    }

    private function getClassName($entity): string
    {
        return $entity instanceof \Doctrine\ORM\Proxy\Proxy ?
            get_parent_class($entity) :
            get_class($entity);
    }
}
