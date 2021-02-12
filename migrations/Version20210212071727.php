<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212071727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cotation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sub_skill_id INT NOT NULL, intership_id INT NOT NULL, evaluation_id INT DEFAULT NULL, cotation INT NOT NULL, INDEX IDX_996DA944A76ED395 (user_id), INDEX IDX_996DA944EF2A2F1B (sub_skill_id), INDEX IDX_996DA9449495B42F (intership_id), INDEX IDX_996DA944456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, week_id INT NOT NULL, date_time DATE NOT NULL, name VARCHAR(15) NOT NULL, INDEX IDX_E5A02990C86F3B2F (week_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, global_evaluation_id INT DEFAULT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_1323A57557D87317 (global_evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE global_evaluation (id INT AUTO_INCREMENT NOT NULL, created_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intership (id INT AUTO_INCREMENT NOT NULL, ects_code VARCHAR(255) NOT NULL, ansco VARCHAR(255) NOT NULL, bloc VARCHAR(255) NOT NULL, section VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, first_day DATE NOT NULL, last_day DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intership_skill (intership_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_74E5B22E9495B42F (intership_id), INDEX IDX_74E5B22E5585C142 (skill_id), PRIMARY KEY(intership_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intership_place (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, intership_id INT NOT NULL, nb_student INT NOT NULL, UNIQUE INDEX UNIQ_5EC50594DA6A219 (place_id), UNIQUE INDEX UNIQ_5EC505949495B42F (intership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, telephone VARCHAR(15) NOT NULL, street LONGTEXT NOT NULL, numero INT NOT NULL, zip_code VARCHAR(20) NOT NULL, town LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, skill_number INT NOT NULL, name LONGTEXT NOT NULL, section VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_day (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, day_id INT NOT NULL, activity LONGTEXT NOT NULL, start_period TIME NOT NULL, end_period TIME NOT NULL, INDEX IDX_717E42E1A76ED395 (user_id), INDEX IDX_717E42E19C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_skill (id INT AUTO_INCREMENT NOT NULL, skill_id INT NOT NULL, number INT NOT NULL, name LONGTEXT NOT NULL, INDEX IDX_B27980CE5585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_intership_place (user_id INT NOT NULL, intership_place_id INT NOT NULL, INDEX IDX_4C19691BA76ED395 (user_id), INDEX IDX_4C19691BA221738 (intership_place_id), PRIMARY KEY(user_id, intership_place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week (id INT AUTO_INCREMENT NOT NULL, first_day DATE NOT NULL, last_day DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA944A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA944EF2A2F1B FOREIGN KEY (sub_skill_id) REFERENCES sub_skill (id)');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA9449495B42F FOREIGN KEY (intership_id) REFERENCES intership (id)');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA944456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57557D87317 FOREIGN KEY (global_evaluation_id) REFERENCES global_evaluation (id)');
        $this->addSql('ALTER TABLE intership_skill ADD CONSTRAINT FK_74E5B22E9495B42F FOREIGN KEY (intership_id) REFERENCES intership (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intership_skill ADD CONSTRAINT FK_74E5B22E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intership_place ADD CONSTRAINT FK_5EC50594DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE intership_place ADD CONSTRAINT FK_5EC505949495B42F FOREIGN KEY (intership_id) REFERENCES intership (id)');
        $this->addSql('ALTER TABLE sub_day ADD CONSTRAINT FK_717E42E1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sub_day ADD CONSTRAINT FK_717E42E19C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE sub_skill ADD CONSTRAINT FK_B27980CE5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE user_intership_place ADD CONSTRAINT FK_4C19691BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_intership_place ADD CONSTRAINT FK_4C19691BA221738 FOREIGN KEY (intership_place_id) REFERENCES intership_place (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sub_day DROP FOREIGN KEY FK_717E42E19C24126');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA944456C5646');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57557D87317');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA9449495B42F');
        $this->addSql('ALTER TABLE intership_skill DROP FOREIGN KEY FK_74E5B22E9495B42F');
        $this->addSql('ALTER TABLE intership_place DROP FOREIGN KEY FK_5EC505949495B42F');
        $this->addSql('ALTER TABLE user_intership_place DROP FOREIGN KEY FK_4C19691BA221738');
        $this->addSql('ALTER TABLE intership_place DROP FOREIGN KEY FK_5EC50594DA6A219');
        $this->addSql('ALTER TABLE intership_skill DROP FOREIGN KEY FK_74E5B22E5585C142');
        $this->addSql('ALTER TABLE sub_skill DROP FOREIGN KEY FK_B27980CE5585C142');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA944EF2A2F1B');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA944A76ED395');
        $this->addSql('ALTER TABLE sub_day DROP FOREIGN KEY FK_717E42E1A76ED395');
        $this->addSql('ALTER TABLE user_intership_place DROP FOREIGN KEY FK_4C19691BA76ED395');
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990C86F3B2F');
        $this->addSql('DROP TABLE cotation');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE global_evaluation');
        $this->addSql('DROP TABLE intership');
        $this->addSql('DROP TABLE intership_skill');
        $this->addSql('DROP TABLE intership_place');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE sub_day');
        $this->addSql('DROP TABLE sub_skill');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_intership_place');
        $this->addSql('DROP TABLE week');
    }
}
