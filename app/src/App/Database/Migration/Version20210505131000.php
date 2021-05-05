<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210505131000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_users ADD latest_activity TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE user_users ADD messages_count INT NOT NULL');
        $this->addSql('ALTER TABLE user_users ADD about_me VARCHAR(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_users.latest_activity IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_users DROP latest_activity');
        $this->addSql('ALTER TABLE user_users DROP messages_count');
        $this->addSql('ALTER TABLE user_users DROP about_me');
    }
}
