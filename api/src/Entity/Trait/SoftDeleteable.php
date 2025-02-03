<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Copy of Gedmo trait with DateTimeImmutable type.
 */
trait SoftDeleteable
{
    #[ApiProperty(writable: false)]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    protected ?DateTimeImmutable $deletedAt;


    public function setDeletedAt(?DateTimeImmutable $deletedAt = null): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }


    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }


    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
