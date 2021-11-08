<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022091735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Dividing product price field into separate table to store price and currency. Dropping "price_gbp" field in "tblProductData" table. Creating table with prices. Creating table with currencies.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO currency (code, description) VALUES (\'GBP\', \'Great Britain Pound\')');
        $this->addSql('CREATE TABLE product_price (id INT AUTO_INCREMENT NOT NULL, product_id INT UNSIGNED NOT NULL, currency_id INT NOT NULL, INDEX IDX_6B9459854584665A (product_id), INDEX IDX_6B94598538248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_price ADD CONSTRAINT FK_6B9459854584665A FOREIGN KEY (product_id) REFERENCES tblProductData (intProductDataId)');
        $this->addSql('ALTER TABLE product_price ADD CONSTRAINT FK_6B94598538248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE tblProductData DROP price_gbp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_price DROP FOREIGN KEY FK_6B94598538248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('ALTER TABLE tblProductData ADD price_gbp NUMERIC(10, 2) DEFAULT NULL');
    }
}
