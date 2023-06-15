<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615152020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, house_number VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, coordinates POINT NOT NULL COMMENT \'(DC2Type:point)\', name VARCHAR(255) DEFAULT NULL, google_link VARCHAR(255) DEFAULT NULL, INDEX IDX_D4E6F8179F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_search (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, user_address VARCHAR(255) NOT NULL, user_cp VARCHAR(255) NOT NULL, user_city VARCHAR(255) NOT NULL, user_coordinates POINT NOT NULL COMMENT \'(DC2Type:point)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', results LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_456E2456A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_search_food_category (restaurant_search_id INT NOT NULL, food_category_id INT NOT NULL, INDEX IDX_4B23D55E55923A8E (restaurant_search_id), INDEX IDX_4B23D55EB3F04B2C (food_category_id), PRIMARY KEY(restaurant_search_id, food_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, shop_id INT NOT NULL, content LONGTEXT DEFAULT NULL, note INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C64D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, delivery TINYINT(1) NOT NULL, take_away TINYINT(1) NOT NULL, avg_price INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_AC6A4CA2F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_food_category (shop_id INT NOT NULL, food_category_id INT NOT NULL, INDEX IDX_301DA98C4D16C4DD (shop_id), INDEX IDX_301DA98CB3F04B2C (food_category_id), PRIMARY KEY(shop_id, food_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8179F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE restaurant_search ADD CONSTRAINT FK_456E2456A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE restaurant_search_food_category ADD CONSTRAINT FK_4B23D55E55923A8E FOREIGN KEY (restaurant_search_id) REFERENCES restaurant_search (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_search_food_category ADD CONSTRAINT FK_4B23D55EB3F04B2C FOREIGN KEY (food_category_id) REFERENCES food_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE shop_food_category ADD CONSTRAINT FK_301DA98C4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_food_category ADD CONSTRAINT FK_301DA98CB3F04B2C FOREIGN KEY (food_category_id) REFERENCES food_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8179F37AE5');
        $this->addSql('ALTER TABLE restaurant_search DROP FOREIGN KEY FK_456E2456A76ED395');
        $this->addSql('ALTER TABLE restaurant_search_food_category DROP FOREIGN KEY FK_4B23D55E55923A8E');
        $this->addSql('ALTER TABLE restaurant_search_food_category DROP FOREIGN KEY FK_4B23D55EB3F04B2C');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64D16C4DD');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2F5B7AF75');
        $this->addSql('ALTER TABLE shop_food_category DROP FOREIGN KEY FK_301DA98C4D16C4DD');
        $this->addSql('ALTER TABLE shop_food_category DROP FOREIGN KEY FK_301DA98CB3F04B2C');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE food_category');
        $this->addSql('DROP TABLE restaurant_search');
        $this->addSql('DROP TABLE restaurant_search_food_category');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE shop_food_category');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
