<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613095358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant_search_food_category (restaurant_search_id INT NOT NULL, food_category_id INT NOT NULL, INDEX IDX_4B23D55E55923A8E (restaurant_search_id), INDEX IDX_4B23D55EB3F04B2C (food_category_id), PRIMARY KEY(restaurant_search_id, food_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_search_food_category ADD CONSTRAINT FK_4B23D55E55923A8E FOREIGN KEY (restaurant_search_id) REFERENCES restaurant_search (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_search_food_category ADD CONSTRAINT FK_4B23D55EB3F04B2C FOREIGN KEY (food_category_id) REFERENCES food_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant_search_food_category DROP FOREIGN KEY FK_4B23D55E55923A8E');
        $this->addSql('ALTER TABLE restaurant_search_food_category DROP FOREIGN KEY FK_4B23D55EB3F04B2C');
        $this->addSql('DROP TABLE restaurant_search_food_category');
    }
}
