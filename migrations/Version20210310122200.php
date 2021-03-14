<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310122200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER NOT NULL, loyalty_card_id INTEGER NOT NULL, total_price DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6117D13B9395C3F3 ON purchase (customer_id)');
        $this->addSql('CREATE INDEX IDX_6117D13B260D7293 ON purchase (loyalty_card_id)');
        $this->addSql('DROP INDEX UNIQ_81398E09E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, firstname, lastname, address, email, telephone, registeration_date FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, address VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, telephone VARCHAR(255) NOT NULL COLLATE BINARY, registration_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO customer (id, firstname, lastname, address, email, telephone, registrationDate) SELECT id, firstname, lastname, address, email, telephone, registeration_date FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON customer (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__loyalty_card AS SELECT id, number, type FROM loyalty_card');
        $this->addSql('DROP TABLE loyalty_card');
        $this->addSql('CREATE TABLE loyalty_card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER DEFAULT NULL, number INTEGER NOT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_EAB8BCBD9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loyalty_card (id, number, type) SELECT id, number, type FROM __temp__loyalty_card');
        $this->addSql('DROP TABLE __temp__loyalty_card');
        $this->addSql('CREATE INDEX IDX_EAB8BCBD9395C3F3 ON loyalty_card (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP INDEX UNIQ_81398E09E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, firstname, lastname, address, email, telephone, registrationDate FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, registeration_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO customer (id, firstname, lastname, address, email, telephone, registeration_date) SELECT id, firstname, lastname, address, email, telephone, registration_date FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON customer (email)');
        $this->addSql('DROP INDEX IDX_EAB8BCBD9395C3F3');
        $this->addSql('CREATE TEMPORARY TABLE __temp__loyalty_card AS SELECT id, number, type FROM loyalty_card');
        $this->addSql('DROP TABLE loyalty_card');
        $this->addSql('CREATE TABLE loyalty_card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, number INTEGER NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO loyalty_card (id, number, type) SELECT id, number, type FROM __temp__loyalty_card');
        $this->addSql('DROP TABLE __temp__loyalty_card');
    }
}
