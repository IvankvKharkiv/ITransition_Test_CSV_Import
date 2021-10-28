<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021080934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration to create empty table "tblProductData" according to existing table structure. DO NOT RUN this migration on productions server where table tblProductData with data already exists.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tblProductData (intProductDataId INT(10) UNSIGNED AUTO_INCREMENT NOT NULL, strProductName VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, strProductDesc VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, strProductCode VARCHAR(10) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, dtmAdded DATETIME DEFAULT NULL, dtmDiscontinued DATETIME DEFAULT NULL, stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL, UNIQUE KEY strProductCode (strProductCode), PRIMARY KEY(intProductDataId)) DEFAULT CHARSET=latin1 ENGINE = InnoDB COMMENT = \'Stores product data\' ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tblProductData');
    }
}
