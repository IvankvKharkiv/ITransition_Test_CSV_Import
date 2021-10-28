<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021084057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding "price_gbp" and "stock" fields to exiting table "tblProductData".';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD price_gbp NUMERIC(10, 2) DEFAULT NULL, ADD stock INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData DROP price_gbp, DROP stock');
    }
}
