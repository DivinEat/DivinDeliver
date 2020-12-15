<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201120182144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA iw');
        $this->addSql('CREATE SEQUENCE iw.book_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE iw.tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE iw.user_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE iw.book (id INT NOT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, publication_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, description TEXT DEFAULT NULL, average_price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_135067961220EA6 ON iw.book (creator_id)');
        $this->addSql('CREATE TABLE book_tag (book_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(book_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_F2F4CE1516A2B381 ON book_tag (book_id)');
        $this->addSql('CREATE INDEX IDX_F2F4CE15BAD26311 ON book_tag (tag_id)');
        $this->addSql('CREATE TABLE iw.tag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE iw.user_account (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B4B816AE7927C74 ON iw.user_account (email)');
        $this->addSql('ALTER TABLE iw.book ADD CONSTRAINT FK_135067961220EA6 FOREIGN KEY (creator_id) REFERENCES iw.user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_tag ADD CONSTRAINT FK_F2F4CE1516A2B381 FOREIGN KEY (book_id) REFERENCES iw.book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_tag ADD CONSTRAINT FK_F2F4CE15BAD26311 FOREIGN KEY (tag_id) REFERENCES iw.tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_tag DROP CONSTRAINT FK_F2F4CE1516A2B381');
        $this->addSql('ALTER TABLE book_tag DROP CONSTRAINT FK_F2F4CE15BAD26311');
        $this->addSql('ALTER TABLE iw.book DROP CONSTRAINT FK_135067961220EA6');
        $this->addSql('DROP SEQUENCE iw.book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE iw.tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE iw.user_account_id_seq CASCADE');
        $this->addSql('DROP TABLE iw.book');
        $this->addSql('DROP TABLE book_tag');
        $this->addSql('DROP TABLE iw.tag');
        $this->addSql('DROP TABLE iw.user_account');
        $this->addSql('DROP SCHEMA iw');
    }
}
