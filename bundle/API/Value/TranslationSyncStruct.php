<?php

namespace Netgen\Bundle\LayoutsRoutingBundle\API\Value;

final class TranslationSyncStruct
{
    private string $name;
    private string $locale;
    private string $payload;

    public function __construct(string $name, string $locale, string $payload)
    {
        $this->name = $name;
        $this->locale = $locale;
        $this->payload = $payload;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }
}
