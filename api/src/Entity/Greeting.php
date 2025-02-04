<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\VersionedSoftDeleteableTrait;
use App\Entity\Trait\TimestampableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Loggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This is a dummy entity. Remove it!
 */
#[ApiResource(mercure: true)]
#[ORM\Entity]
#[Gedmo\SoftDeleteable(timeAware: true)]
#[Gedmo\Loggable(logEntryClass: LogEntry::class)]
class Greeting implements Loggable
{
    use VersionedSoftDeleteableTrait;
    use TimestampableEntityTrait;


    /**
     * The entity ID
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[Gedmo\Versioned]
    private ?int $id = null;

    /**
     * A nice person
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Gedmo\Versioned]
    public string $name = '';

    public function getId(): ?int
    {
        return $this->id;
    }
}
