<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726114240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD DATE_CREATION_ANNONCE DATETIME NOT NULL, CHANGE ID_ETAT ID_ETAT INT DEFAULT NULL, CHANGE ID_UTILISATEUR ID_UTILISATEUR INT DEFAULT NULL, CHANGE ID_SOUS_CATEGORIE ID_SOUS_CATEGORIE INT DEFAULT NULL, CHANGE ID_VILLE ID_VILLE INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation CHANGE ID_UTILISATEUR ID_UTILISATEUR INT DEFAULT NULL, CHANGE ID_ANNONCE ID_ANNONCE INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE ID_ANNONCE ID_ANNONCE INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE ID_DISPONIBILITE ID_DISPONIBILITE INT DEFAULT NULL, CHANGE ID_EVALUATION ID_EVALUATION INT DEFAULT NULL, CHANGE ID_ANNONCE ID_ANNONCE INT DEFAULT NULL, CHANGE ID_UTILISATEUR ID_UTILISATEUR INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_categorie CHANGE ID_CATEGORIE ID_CATEGORIE INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP MAIL_UTILISATEUR');
        $this->addSql('ALTER TABLE ville CHANGE ID_DEPARTEMENT ID_DEPARTEMENT INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE annonce DROP DATE_CREATION_ANNONCE, CHANGE ID_SOUS_CATEGORIE ID_SOUS_CATEGORIE INT NOT NULL, CHANGE ID_VILLE ID_VILLE INT NOT NULL, CHANGE ID_ETAT ID_ETAT INT NOT NULL, CHANGE ID_UTILISATEUR ID_UTILISATEUR INT NOT NULL');
        $this->addSql('ALTER TABLE conversation CHANGE ID_ANNONCE ID_ANNONCE INT NOT NULL, CHANGE ID_UTILISATEUR ID_UTILISATEUR INT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE ID_ANNONCE ID_ANNONCE INT NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE ID_ANNONCE ID_ANNONCE INT NOT NULL, CHANGE ID_EVALUATION ID_EVALUATION INT NOT NULL, CHANGE ID_DISPONIBILITE ID_DISPONIBILITE INT NOT NULL, CHANGE ID_UTILISATEUR ID_UTILISATEUR INT NOT NULL');
        $this->addSql('ALTER TABLE sous_categorie CHANGE ID_CATEGORIE ID_CATEGORIE INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD MAIL_UTILISATEUR VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE ville CHANGE ID_DEPARTEMENT ID_DEPARTEMENT INT NOT NULL');
    }
}
