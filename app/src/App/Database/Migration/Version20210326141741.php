<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210326141741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE messenger_messages (uuid UUID NOT NULL, author_id UUID NOT ' .
            'NULL, wrote_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, content VARCHAR(1024) NOT NULL, ' .
            'edit_status VARCHAR(16) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_75EA56E0F675F31B ON messenger_messages (author_id)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.uuid IS \'(DC2Type:messenger_message_id)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.author_id IS \'(DC2Type:messenger_author_id)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.wrote_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.content IS \'(DC2Type:messenger_message_content)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.edit_status IS' .
            ' \'(DC2Type:messenger_message_edit_status)\'');
        $this->addSql('ALTER TABLE messenger_messages ADD CONSTRAINT FK_75EA56E0F675F31B FOREIGN KEY ' .
            '(author_id) REFERENCES messenger_authors (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
