<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210320093831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_users (uuid UUID NOT NULL, username VARCHAR(16) NOT NULL, hash ' .
            'VARCHAR(128) NOT NULL, status VARCHAR(16) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1F85E0677 ON user_users (username)');
        $this->addSql('COMMENT ON COLUMN user_users.uuid IS \'(DC2Type:user_user_id)\'');
        $this->addSql('COMMENT ON COLUMN user_users.username IS \'(DC2Type:user_user_username)\'');
        $this->addSql('COMMENT ON COLUMN user_users.status IS \'(DC2Type:user_user_status)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE user_users');
    }
}
