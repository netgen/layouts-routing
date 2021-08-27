<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\API\Value;

use Netgen\Bundle\LayoutsRoutingBundle\Entity\Content;
use Netgen\Bundle\LayoutsRoutingBundle\Entity\ContentTranslation;
use Symfony\Component\Routing\Route;

abstract class DomainContent
{
    private Content $content;
    private ContentTranslation $translation;
    private Route $route;
    private string $type;

    public function __construct(
        Content $content,
        ContentTranslation $translation,
        Route $route,
        string $type
    ) {
        $this->translation = $translation;
        $this->content = $content;
        $this->route = $route;
        $this->type = $type;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getTranslation(): ContentTranslation
    {
        return $this->translation;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
