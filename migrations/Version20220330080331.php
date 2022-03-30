<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330080331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE image_name image_name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE status CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`, CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
