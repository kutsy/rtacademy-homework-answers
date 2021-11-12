<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211112072300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9A19060E16C6B94 ON post_category (alias)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B2281795E237E06 ON post_status (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B9A19060E16C6B94 ON post_category');
        $this->addSql('DROP INDEX UNIQ_4B2281795E237E06 ON post_status');
    }
}
