<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518161955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visuel ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE visuel ADD CONSTRAINT FK_8FA54D1B7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_8FA54D1B7294869C ON visuel (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visuel DROP FOREIGN KEY FK_8FA54D1B7294869C');
        $this->addSql('DROP INDEX IDX_8FA54D1B7294869C ON visuel');
        $this->addSql('ALTER TABLE visuel DROP article_id');
    }
}
