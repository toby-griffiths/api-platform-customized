<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250203230443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add soft delete column';
    }


    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE greeting ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }


    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE greeting DROP deleted_at');
    }
}
