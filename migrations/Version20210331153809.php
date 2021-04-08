<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331153809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config CHANGE value value VARCHAR(255) DEFAULT NULL, CHANGE value_num value_num NUMERIC(10, 2) DEFAULT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE declaration_honneur CHANGE id_salarie id_salarie INT DEFAULT NULL, CHANGE id_ville id_ville INT DEFAULT NULL, CHANGE id_entreprise id_entreprise INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE id_ville id_ville INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mois CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE parcours ADD id_type_trajet INT DEFAULT NULL, CHANGE id_salarie id_salarie INT DEFAULT NULL, CHANGE id_mois id_mois INT DEFAULT NULL, CHANGE cloture cloture TINYINT(1) NOT NULL, CHANGE validation validation TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE3109E0F43 FOREIGN KEY (id_type_trajet) REFERENCES type_trajet (id)');
        $this->addSql('CREATE INDEX IDX_99B1DEE3109E0F43 ON parcours (id_type_trajet)');
        $this->addSql('ALTER TABLE parcours_date CHANGE id_parcours id_parcours INT DEFAULT NULL, CHANGE id_type_trajet id_type_trajet INT DEFAULT NULL');
        $this->addSql('ALTER TABLE salarie CHANGE id_ville id_ville INT DEFAULT NULL, CHANGE id_entreprise id_entreprise INT DEFAULT NULL, CHANGE id_service id_service INT DEFAULT NULL, CHANGE id_role id_role INT DEFAULT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE type_trajet CHANGE coef_km coef_km DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ville CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX ville_code_commune ON villes_france_free');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config CHANGE value value VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE value_num value_num NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE actif actif TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE declaration_honneur CHANGE id_entreprise id_entreprise INT NOT NULL, CHANGE id_salarie id_salarie INT NOT NULL, CHANGE id_ville id_ville INT NOT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE id_ville id_ville INT NOT NULL');
        $this->addSql('ALTER TABLE mois CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE3109E0F43');
        $this->addSql('DROP INDEX IDX_99B1DEE3109E0F43 ON parcours');
        $this->addSql('ALTER TABLE parcours DROP id_type_trajet, CHANGE id_mois id_mois INT NOT NULL, CHANGE id_salarie id_salarie INT NOT NULL, CHANGE cloture cloture TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE validation validation TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE parcours_date CHANGE id_type_trajet id_type_trajet INT NOT NULL, CHANGE id_parcours id_parcours INT NOT NULL');
        $this->addSql('ALTER TABLE salarie CHANGE id_ville id_ville INT NOT NULL, CHANGE id_service id_service INT NOT NULL, CHANGE id_entreprise id_entreprise INT NOT NULL, CHANGE id_role id_role INT NOT NULL, CHANGE actif actif TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE type_trajet CHANGE coef_km coef_km DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ville CHANGE id id INT NOT NULL, CHANGE actif actif TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE INDEX ville_code_commune ON villes_france_free (ville_code_commune)');
    }
}
