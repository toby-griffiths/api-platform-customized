<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250203224311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at and updated_at columns';
    }


    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE greeting ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE greeting ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }


    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE greeting DROP created_at');
        $this->addSql('ALTER TABLE greeting DROP updated_at');
    }
}
