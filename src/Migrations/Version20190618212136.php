<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618212136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(170) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, video_id INT NOT NULL, user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, user_name VARCHAR(255) NOT NULL, parent_id INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9474526C29C1004E (video_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(180) DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, avatar_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, email VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, slug VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), UNIQUE INDEX UNIQ_389B783989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_api (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_742D6553A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial (id INT AUTO_INCREMENT NOT NULL, folder_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, image_path VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C66BFFE9162CB942 (folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial_category (tutorial_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_D652884189366B7B (tutorial_id), INDEX IDX_D652884112469DE2 (category_id), PRIMARY KEY(tutorial_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_profile_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', first_name VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, agreed_terms_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6496B9DD454 (user_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tutorial (user_id INT NOT NULL, tutorial_id INT NOT NULL, INDEX IDX_26E61BE9A76ED395 (user_id), INDEX IDX_26E61BE989366B7B (tutorial_id), PRIMARY KEY(user_id, tutorial_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, tutorial_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, video_content LONGTEXT DEFAULT NULL, published_at DATETIME DEFAULT NULL, slug VARCHAR(180) DEFAULT NULL, video_path VARCHAR(255) DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7CC7DA2CF675F31B (author_id), INDEX IDX_7CC7DA2C89366B7B (tutorial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_tag (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F910728729C1004E (video_id), INDEX IDX_F9107287BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C29C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE token_api ADD CONSTRAINT FK_742D6553A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT FK_C66BFFE9162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE tutorial_category ADD CONSTRAINT FK_D652884189366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_category ADD CONSTRAINT FK_D652884112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B9DD454 FOREIGN KEY (user_profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE user_tutorial ADD CONSTRAINT FK_26E61BE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tutorial ADD CONSTRAINT FK_26E61BE989366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F910728729C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F9107287BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
        	$this
				->connection
				->getDatabasePlatform()
				->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutorial_category DROP FOREIGN KEY FK_D652884112469DE2');
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY FK_C66BFFE9162CB942');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B9DD454');
        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F9107287BAD26311');
        $this->addSql('ALTER TABLE tutorial_category DROP FOREIGN KEY FK_D652884189366B7B');
        $this->addSql('ALTER TABLE user_tutorial DROP FOREIGN KEY FK_26E61BE989366B7B');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C89366B7B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE token_api DROP FOREIGN KEY FK_742D6553A76ED395');
        $this->addSql('ALTER TABLE user_tutorial DROP FOREIGN KEY FK_26E61BE9A76ED395');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C29C1004E');
        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F910728729C1004E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE token_api');
        $this->addSql('DROP TABLE tutorial');
        $this->addSql('DROP TABLE tutorial_category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_tutorial');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE video_tag');
    }
}
