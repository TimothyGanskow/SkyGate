<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127161614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registry DROP FOREIGN KEY FK_CDA81D0ABFDC1EC5');
        $this->addSql('DROP INDEX IDX_CDA81D0ABFDC1EC5 ON registry');
        $this->addSql('ALTER TABLE registry DROP user_id_reg_id');
        $this->addSql('ALTER TABLE userspermission DROP FOREIGN KEY FK_CBA8495683997E73');
        $this->addSql('DROP INDEX IDX_CBA8495683997E73 ON userspermission');
        $this->addSql('ALTER TABLE userspermission DROP user_id_perm_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registry ADD user_id_reg_id INT NOT NULL');
        $this->addSql('ALTER TABLE registry ADD CONSTRAINT FK_CDA81D0ABFDC1EC5 FOREIGN KEY (user_id_reg_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CDA81D0ABFDC1EC5 ON registry (user_id_reg_id)');
        $this->addSql('ALTER TABLE userspermission ADD user_id_perm_id INT NOT NULL');
        $this->addSql('ALTER TABLE userspermission ADD CONSTRAINT FK_CBA8495683997E73 FOREIGN KEY (user_id_perm_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_CBA8495683997E73 ON userspermission (user_id_perm_id)');
    }
}
