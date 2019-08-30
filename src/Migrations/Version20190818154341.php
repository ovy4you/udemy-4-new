<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190818154341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, published DATETIME NOT NULL, text CLOB NOT NULL, slug VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BA5AE01DF675F31B ON blog_post (author_id)');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, blog_post_id INTEGER DEFAULT NULL, content CLOB NOT NULL, published DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA77FBEAF ON comment (blog_post_id)');
        $this->addSql('CREATE TABLE micro_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, time DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_2AEFE017A76ED395 ON micro_post (user_id)');
        $this->addSql('CREATE TABLE profile_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, fullname VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('CREATE TABLE following (user_id INTEGER NOT NULL, following_user_id INTEGER NOT NULL, PRIMARY KEY(user_id, following_user_id))');
        $this->addSql('CREATE INDEX IDX_71BF8DE3A76ED395 ON following (user_id)');
        $this->addSql('CREATE INDEX IDX_71BF8DE31896F387 ON following (following_user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE micro_post');
        $this->addSql('DROP TABLE profile_user');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE user');
    }
}
