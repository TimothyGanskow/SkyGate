<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127151544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD registry_id_id INT NOT NULL, ADD permission_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DD3F3667 FOREIGN KEY (registry_id_id) REFERENCES registry (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A9C3F324 FOREIGN KEY (permission_id_id) REFERENCES userspermission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9DD3F3667 ON users (registry_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A9C3F324 ON users (permission_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9DD3F3667');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9A9C3F324');
        $this->addSql('DROP INDEX UNIQ_1483A5E9DD3F3667 ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E9A9C3F324 ON users');
        $this->addSql('ALTER TABLE users DROP registry_id_id, DROP permission_id_id');
    }
}
