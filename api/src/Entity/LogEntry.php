<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Loggable\LogEntryInterface;
use Gedmo\Loggable\Loggable;
use Symfony\Component\Clock\DatePoint;

/**
 * @template T of Loggable
 */
#[ORM\Entity(repositoryClass: LogEntryRepository::class)]
#[ORM\Table(name: 'log_entries', options: ['row_format' => 'DYNAMIC'])]
#[ORM\Index(name: 'log_class_lookup_idx', columns: ['object_class'])]
#[ORM\Index(name: 'log_date_lookup_idx', columns: ['logged_at'])]
#[ORM\Index(name: 'log_user_lookup_idx', columns: ['username'])]
#[ORM\Index(name: 'log_version_lookup_idx', columns: ['object_id', 'object_class', 'version'])]
class LogEntry implements LogEntryInterface
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    protected ?int $id;

    /** @var self::ACTION_CREATE|self::ACTION_UPDATE|self::ACTION_REMOVE|null */
    #[ORM\Column(length: 8)]
    protected ?string $action;

    #[ORM\Column]
    protected DateTimeImmutable $loggedAt;

    #[ORM\Column(length: 64, nullable: true)]
    protected ?string $objectId;

    /** @var class-string<T>|null */
    #[ORM\Column(length: 191)]
    protected ?string $objectClass;

    #[ORM\Column]
    protected ?int $version;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    protected ?array $data;

    #[ORM\Column(length: 191, nullable: true)]
    protected ?string $username;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAction(): ?string
    {
        return $this->action;
    }


    public function setAction(string $action): void
    {
        $this->action = $action;
    }


    /** @return class-string<T>|null */
    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }


    /** @psalm-param class-string<T> $objectClass */
    public function setObjectClass(string $objectClass): void
    {
        $this->objectClass = $objectClass;
    }


    public function getObjectId(): ?string
    {
        return $this->objectId;
    }


    public function setObjectId(string $objectId): void
    {
        $this->objectId = $objectId;
    }


    public function getUsername(): ?string
    {
        return $this->username;
    }


    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }


    public function getLoggedAt(): ?DateTimeImmutable
    {
        return $this->loggedAt;
    }


    public function setLoggedAt(): void
    {
        $this->loggedAt = DateTimeImmutable::createFromInterface(new DatePoint());
    }


    public function getData(): ?array
    {
        return $this->data;
    }


    /** @param array<string, mixed> $data */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


    public function setVersion(int $version): void
    {
        $this->version = $version;
    }


    public function getVersion(): ?int
    {
        return $this->version;
    }
}
