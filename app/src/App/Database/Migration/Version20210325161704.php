<?php

declare(strict_types=1);

namespace Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210325161704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE messenger_authors (uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ' .
            'ZONE NOT NULL, messages_count INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('COMMENT ON COLUMN messenger_authors.uuid IS \'(DC2Type:messenger_author_id)\'');
        $this->addSql('COMMENT ON COLUMN messenger_authors.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE oauth2_authorization_code DROP CONSTRAINT FK_509FEF5FC7440455');
        $this->addSql('ALTER TABLE oauth2_authorization_code ADD CONSTRAINT FK_509FEF5FC7440455 FOREIGN ' .
            'KEY (client) REFERENCES oauth2_client (identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE messenger_authors');
        $this->addSql('ALTER TABLE oauth2_authorization_code DROP CONSTRAINT fk_509fef5fc7440455');
        $this->addSql('ALTER TABLE oauth2_authorization_code ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY ' .
            '(client) REFERENCES oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
