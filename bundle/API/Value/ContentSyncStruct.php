<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\API\Value;

final class ContentSyncStruct
{
    private string $remoteId;
    private string $type;

    /**
     * @var \Netgen\Bundle\LayoutsRoutingBundle\API\Value\TranslationSyncStruct[]
     */
    private array $translationMap;

    public function __construct(string $remoteId, string $type, array $translations = [])
    {
        $this->remoteId = $remoteId;
        $this->type = $type;

        foreach ($translations as $translation) {
            $this->addTranslation($translation);
        }
    }

    public function addTranslation(TranslationSyncStruct $translation): void
    {
        $this->translationMap[$translation->getLocale()] = $translation;
    }

    public function getRemoteId(): string
    {
        return $this->remoteId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \Netgen\Bundle\LayoutsRoutingBundle\API\Value\TranslationSyncStruct[]
     */
    public function getTranslations(): array
    {
        return array_values($this->translationMap);
    }

    public function hasTranslation(string $locale): bool
    {
        return array_key_exists($locale, $this->translationMap);
    }
}
