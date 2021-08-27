<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Core;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Netgen\Bundle\LayoutsRoutingBundle\API\ContentSyncerInterface;
use Netgen\Bundle\LayoutsRoutingBundle\API\Value\ContentSyncStruct;
use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;
use Netgen\Bundle\LayoutsRoutingBundle\Repository\ContentRepository;
use Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\AutoRoute;

final class ContentSyncer implements ContentSyncerInterface
{
    private ContentRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(ContentRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function sync(ContentSyncStruct $struct): Content
    {
        $content = $this->resolveContent($struct);

        $this->entityManager->persist($content);
        $this->entityManager->flush();

        return $content;
    }

    public function syncMultiple(array $structs): array
    {
        $contentItems = [];

        foreach ($structs as $struct) {
            $content = $this->resolveContent($struct);

            $this->entityManager->persist($content);

            $contentItems[] = $content;
        }

        $this->entityManager->flush();

        return $contentItems;
    }

    public function remove(Content $content): void
    {
        $this->entityManager->remove($content);
        $this->entityManager->flush();
    }

    public function removeOrphanedRoutes(): void
    {
        throw new \RuntimeException('Not implemented');
    }

    private function resolveContent(ContentSyncStruct $struct): Content
    {
        $content = $this->repository->findByRemoteId($struct->getRemoteId());

        if ($content instanceof Content) {
            $this->updateContent($content, $struct);
        }

        return $content ?? $this->buildContent($struct);
    }

    private function updateContent(Content $content, ContentSyncStruct $struct): void
    {
        foreach ($struct->getTranslations() as $translationStruct) {
            /** @var \Netgen\Bundle\LayoutsRoutingBundle\Entity\ContentTranslation $contentTranslation */
            $contentTranslation = $content->translate($translationStruct->getLocale());

            $contentTranslation->setName($translationStruct->getName());
            $contentTranslation->setPayload($translationStruct->getPayload());
        }

        foreach ($content->getTranslations() as $contentTranslation) {
            if (!$struct->hasTranslation($contentTranslation->getLocale())) {
                $content->removeTranslation($contentTranslation);
            }
        }

        $content->mergeNewTranslations();
    }

    private function buildContent(ContentSyncStruct $struct): Content
    {
        $content = Content::fromRemoteIdAndType($struct->getRemoteId(), $struct->getType());

        foreach ($struct->getTranslations() as $translation) {
            /** @var \Netgen\Bundle\LayoutsRoutingBundle\Entity\ContentTranslation $contentTranslation */
            $contentTranslation = $content->translate($translation->getLocale());
            $contentTranslation->setName($translation->getName());
            $contentTranslation->setPayload($translation->getPayload());
        }

        $content->mergeNewTranslations();

        return $content;
    }
}
