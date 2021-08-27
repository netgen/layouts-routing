<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Symfony\Cmf\Bundle\RoutingAutoOrmBundle\Doctrine\Orm\MultiRouteTrait;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;

// todo remove multi route trait and route referrers interface, test

/**
 * @ORM\Entity()
 * @ORM\Table(name="nglayouts_routing_content")
 */
final class Content implements RouteReferrersInterface, JsonSerializable, TranslatableInterface
{
    use MultiRouteTrait;
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="guid")
     */
    private string $remoteId;

    /**
     * @ORM\Column(type="string")
     */
    private string $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRemoteId(): string
    {
        return $this->remoteId;
    }

    public function setRemoteId(string $remoteId): self
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->translate(null, false)->getName();
    }

    public function setName(string $name): void
    {
        $this->translate(null, false)->setName($name);
    }

    public static function fromRemoteIdAndType(string $remoteId, string $type): self
    {
        $content = new self;
        $content->remoteId = $remoteId;
        $content->type = $type;

        return $content;
    }

    /**
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale($locale): self
    {
        $this->setCurrentLocale($locale);

        return $this;
    }

    public function getLocale()
    {
        return $this->getCurrentLocale();
    }
}
