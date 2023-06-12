<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612183756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_search ADD user_id INT DEFAULT NULL, ADD results LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE restaurant_search ADD CONSTRAINT FK_456E2456A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_456E2456A76ED395 ON restaurant_search (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_search DROP FOREIGN KEY FK_456E2456A76ED395');
        $this->addSql('DROP INDEX IDX_456E2456A76ED395 ON restaurant_search');
        $this->addSql('ALTER TABLE restaurant_search DROP user_id, DROP results');
    }
}
