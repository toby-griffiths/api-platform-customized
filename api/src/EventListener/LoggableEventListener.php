<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Gedmo\Mapping\Annotation\Loggable;
use Gedmo\Mapping\Annotation\Versioned;
use LogicException;
use ReflectionException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

use function implode;

/**
 * Listener used to ensure that all properties on an entity are versioned.
 */
#[AsEventListener(event: ConsoleEvents::COMMAND, method: 'detectCommand')]
#[AsEventListener(event: ConsoleEvents::TERMINATE, method: 'remindToAddVersionedAttribute')]
#[AsDoctrineListener(event: Events::loadClassMetadata, priority: 500, connection: 'default')]
final class LoggableEventListener
{
    private bool $isMakingEntity = false;


    public function detectCommand(ConsoleCommandEvent $event): void
    {
        if ('make:entity' === $event->getCommand()?->getName()) {
            $this->isMakingEntity = true;
        }
    }


    /**
     * @throws ReflectionException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        if ($this->isMakingEntity) {
            return;
        }

        $ignoredClasses = [
            AbstractLogEntry::class,
            LogEntry::class,
        ];

        if (in_array($eventArgs->getClassMetadata()->getName(), $ignoredClasses, true)) {
            return;
        }

        $classMetadata = $eventArgs->getClassMetadata();
        $reflectionClass = $classMetadata->getReflectionClass();
        $loggableAttribs = $reflectionClass->getAttributes(Loggable::class);
        if (empty($loggableAttribs)) {
            throw new LogicException(
                sprintf('Class %s is missing the %s attribute.', $classMetadata->getName(), Loggable::class),
            );
        }

        $missingVersionedFields = [];
        foreach ($classMetadata->getFieldNames() as $fieldName) {
            if (empty($reflectionClass->getProperty($fieldName)->getAttributes(Versioned::class))) {
                $missingVersionedFields[] = $fieldName;
            }
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $associationMapping) {
            $hasVersionedAttrib = !empty($reflectionClass->getProperty($fieldName)->getAttributes(Versioned::class));

            if (!$associationMapping['isOwningSide'] && !$hasVersionedAttrib) {
                continue;
            }

            if (!$associationMapping['isOwningSide'] && $hasVersionedAttrib) {
                throw new LogicException(
                    sprintf(
                        'The %s attribute should only be on the owning side of an association, and not on %s::$%s.',
                        Versioned::class,
                        $classMetadata->getName(),
                        $fieldName,
                    ),
                );
            }

            $fieldName = $associationMapping['fieldName'];
            if (!$hasVersionedAttrib) {
                $missingVersionedFields[] = $fieldName;
            }
        }

        if (!empty($missingVersionedFields)) {
            throw new LogicException(
                sprintf(
                    'All properties of %s should have the %s attribute, but "%s" do not.',
                    Versioned::class,
                    $classMetadata->getName(),
                    implode('", "', $missingVersionedFields),
                ),
            );
        }
    }


    public function remindToAddVersionedAttribute(ConsoleTerminateEvent $event): void
    {
        if (!$this->isMakingEntity) {
            return;
        }

        (new SymfonyStyle($event->getInput(), $event->getOutput()))
            ->warning('Don\'t forget to add the ' . Versioned::class . ' attribute to your entity properties!');
    }
}
