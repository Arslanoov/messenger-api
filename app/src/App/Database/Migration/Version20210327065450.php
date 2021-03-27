<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210327065450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE messenger_messages ADD read_status VARCHAR(16) NOT NULL');
        $this->addSql('COMMENT ON COLUMN messenger_messages.read_status IS' .
            ' \'(DC2Type:messenger_message_read_status)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE messenger_messages DROP read_status');
    }
}
