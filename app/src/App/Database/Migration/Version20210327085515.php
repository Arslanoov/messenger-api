<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210327085515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE messenger_dialogs (uuid UUID NOT NULL, first_author_id ' .
            'UUID NOT NULL, second_author_id UUID NOT NULL, messages_count INT NOT NULL, not_read_count ' .
            'INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_81D5924FA786355 ON messenger_dialogs (first_author_id)');
        $this->addSql('CREATE INDEX IDX_81D5924FCB9E7293 ON messenger_dialogs (second_author_id)');
        $this->addSql('COMMENT ON COLUMN messenger_dialogs.uuid IS \'(DC2Type:messenger_dialog_id)\'');
        $this->addSql('COMMENT ON COLUMN messenger_dialogs.first_author_id IS \'(DC2Type:messenger_author_id)\'');
        $this->addSql('COMMENT ON COLUMN messenger_dialogs.second_author_id IS \'(DC2Type:messenger_author_id)\'');
        $this->addSql('ALTER TABLE messenger_dialogs ADD CONSTRAINT FK_81D5924FA786355 FOREIGN KEY' .
            ' (first_author_id) REFERENCES messenger_authors (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messenger_dialogs ADD CONSTRAINT FK_81D5924FCB9E7293 FOREIGN KEY' .
            ' (second_author_id) REFERENCES messenger_authors (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE messenger_messages ADD dialog_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN messenger_messages.dialog_id IS \'(DC2Type:messenger_dialog_id)\'');
        $this->addSql('ALTER TABLE messenger_messages ADD CONSTRAINT FK_75EA56E05E46C4E2 FOREIGN ' .
            'KEY (dialog_id) REFERENCES messenger_dialogs (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_75EA56E05E46C4E2 ON messenger_messages (dialog_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE messenger_messages DROP CONSTRAINT FK_75EA56E05E46C4E2');
        $this->addSql('DROP TABLE messenger_dialogs');
        $this->addSql('DROP INDEX IDX_75EA56E05E46C4E2');
        $this->addSql('ALTER TABLE messenger_messages DROP dialog_id');
    }
}
