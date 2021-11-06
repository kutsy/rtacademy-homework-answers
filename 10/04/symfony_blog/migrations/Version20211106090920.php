<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211106090920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, category_id_id INT NOT NULL, cover_id_id INT NOT NULL, status_id_id INT NOT NULL, title VARCHAR(128) NOT NULL, alias VARCHAR(128) NOT NULL, description VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, publish_date DATETIME NOT NULL, INDEX IDX_5A8A6C8D69CCBE9A (author_id_id), INDEX IDX_5A8A6C8D9777D11E (category_id_id), INDEX IDX_5A8A6C8DA6A618C2 (cover_id_id), INDEX IDX_5A8A6C8D881ECFA7 (status_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, alias VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_cover (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(128) NOT NULL, alt VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, title VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(32) NOT NULL, password VARCHAR(128) NOT NULL, email VARCHAR(255) NOT NULL, lastname VARCHAR(128) NOT NULL, firstname VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, href VARCHAR(255) NOT NULL, order_position SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D69CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9777D11E FOREIGN KEY (category_id_id) REFERENCES post_category (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA6A618C2 FOREIGN KEY (cover_id_id) REFERENCES post_cover (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D881ECFA7 FOREIGN KEY (status_id_id) REFERENCES post_status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9777D11E');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA6A618C2');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D881ECFA7');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D69CCBE9A');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE post_cover');
        $this->addSql('DROP TABLE post_status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE website_menu');
    }
}
