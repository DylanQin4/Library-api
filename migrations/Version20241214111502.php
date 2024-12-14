<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214111502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id SERIAL NOT NULL, book_id INT NOT NULL, loaned_by_id INT NOT NULL, loan_date DATE NOT NULL, return_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5D30D0316A2B381 ON loan (book_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0349CA1B72 ON loan (loaned_by_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, first_name VARCHAR(150) NOT NULL, last_name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0349CA1B72 FOREIGN KEY (loaned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE loan DROP CONSTRAINT FK_C5D30D0316A2B381');
        $this->addSql('ALTER TABLE loan DROP CONSTRAINT FK_C5D30D0349CA1B72');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE "user"');
    }
}
