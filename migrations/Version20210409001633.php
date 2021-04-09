<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409001633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE items_to_options (id INT AUTO_INCREMENT NOT NULL, linked_option_id INT DEFAULT NULL, linked_item_id INT DEFAULT NULL, INDEX IDX_C0B5F945B1C6D264 (linked_option_id), INDEX IDX_C0B5F94579276AC9 (linked_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE items_to_options ADD CONSTRAINT FK_C0B5F945B1C6D264 FOREIGN KEY (linked_option_id) REFERENCES options (id)');
        $this->addSql('ALTER TABLE items_to_options ADD CONSTRAINT FK_C0B5F94579276AC9 FOREIGN KEY (linked_item_id) REFERENCES option_items (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE items_to_options');
    }
}
