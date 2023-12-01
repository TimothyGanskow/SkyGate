<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127165254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registry DROP FOREIGN KEY FK_CDA81D0ABFDC1EC5');
        $this->addSql('DROP INDEX UNIQ_CDA81D0ABFDC1EC5 ON registry');
        $this->addSql('ALTER TABLE registry DROP user_id_reg_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E923A908CF');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9445278BA');
        $this->addSql('DROP INDEX UNIQ_1483A5E9445278BA ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E923A908CF ON users');
        $this->addSql('ALTER TABLE users DROP regdetails_id, DROP permdetails_id');
        $this->addSql('ALTER TABLE userspermission DROP FOREIGN KEY FK_CBA8495683997E73');
        $this->addSql('DROP INDEX UNIQ_CBA8495683997E73 ON userspermission');
        $this->addSql('ALTER TABLE userspermission DROP user_id_perm_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registry ADD user_id_reg_id INT NOT NULL');
        $this->addSql('ALTER TABLE registry ADD CONSTRAINT FK_CDA81D0ABFDC1EC5 FOREIGN KEY (user_id_reg_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDA81D0ABFDC1EC5 ON registry (user_id_reg_id)');
        $this->addSql('ALTER TABLE users ADD regdetails_id INT NOT NULL, ADD permdetails_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E923A908CF FOREIGN KEY (permdetails_id) REFERENCES userspermission (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9445278BA FOREIGN KEY (regdetails_id) REFERENCES registry (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9445278BA ON users (regdetails_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E923A908CF ON users (permdetails_id)');
        $this->addSql('ALTER TABLE userspermission ADD user_id_perm_id INT NOT NULL');
        $this->addSql('ALTER TABLE userspermission ADD CONSTRAINT FK_CBA8495683997E73 FOREIGN KEY (user_id_perm_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBA8495683997E73 ON userspermission (user_id_perm_id)');
    }
}
