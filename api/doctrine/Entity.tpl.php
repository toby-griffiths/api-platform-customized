<?php

declare(strict_types=1);

use App\Entity\Trait\TimestampableEntityTrait;
use Symfony\Bundle\MakerBundle\Maker\Common\EntityIdTypeEnum;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;

assert(isset($use_statements) && $use_statements instanceof UseStatementGenerator);
$use_statements->addUseStatement(
    [
        TimestampableEntityTrait::class,
    ],
);

?>
<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

<?= $use_statements; ?>

#[ORM\Entity(repositoryClass: <?= $repository_class_name ?>::class)]
<?php if ($should_escape_table_name): ?>#[ORM\Table(name: '`<?= $table_name ?>`')]
<?php endif ?>
<?php if ($api_resource): ?>
    #[ApiResource]
<?php endif ?>
<?php if ($broadcast): ?>
    #[Broadcast]
<?php endif ?>
class <?= $class_name . "\n" ?>
{
    use TimestampableEntityTrait;


<?php if (EntityIdTypeEnum::UUID === $id_type): ?>
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    public function getId(): ?Uuid
    {
    return $this->id;
    }
<?php elseif (EntityIdTypeEnum::ULID === $id_type): ?>
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    public function getId(): ?Ulid
    {
    return $this->id;
    }
<?php else: ?>
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
    return $this->id;
    }
<?php endif ?>
}
