<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250616160745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(64) NOT NULL, UNIQUE INDEX uq_categories_title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', content LONGTEXT NOT NULL, INDEX IDX_5F9E962A4B89032C (post_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', content LONGTEXT NOT NULL, INDEX IDX_885DBAFA12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE posts_tags (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_D5ECAD9F4B89032C (post_id), INDEX IDX_D5ECAD9FBAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', title VARCHAR(64) NOT NULL, slug VARCHAR(64) NOT NULL, UNIQUE INDEX uq_tags_title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts_tags ADD CONSTRAINT FK_D5ECAD9F4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts_tags ADD CONSTRAINT FK_D5ECAD9FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts_tags DROP FOREIGN KEY FK_D5ECAD9F4B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE posts_tags DROP FOREIGN KEY FK_D5ECAD9FBAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE posts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE posts_tags
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tags
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
