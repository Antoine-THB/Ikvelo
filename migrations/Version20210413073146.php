<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413073146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, justificatif VARCHAR(255) NOT NULL, id_salarie INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE declaration_honneur CHANGE url_geovelo url_geovelo VARCHAR(255) DEFAULT \'NULL\', CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated updated DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE adresse adresse VARCHAR(255) DEFAULT \'NULL\', CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated updated DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE mois CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_type_trajet');
        $this->addSql('ALTER TABLE parcours CHANGE id_salarie id_salarie INT DEFAULT NULL, CHANGE id_mois id_mois INT DEFAULT NULL, CHANGE descript_trajet descript_trajet VARCHAR(255) DEFAULT \'NULL\', CHANGE nb_km_effectue nb_km_effectue NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE indemnisation indemnisation NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE cloture cloture TINYINT(1) NOT NULL, CHANGE validation validation TINYINT(1) NOT NULL, CHANGE commentaire commentaire VARCHAR(255) DEFAULT \'NULL\', CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated updated DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('DROP INDEX fk_type_trajet ON parcours');
        $this->addSql('CREATE INDEX IDX_99B1DEE3109E0F43 ON parcours (id_type_trajet)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_type_trajet FOREIGN KEY (id_type_trajet) REFERENCES type_trajet (id)');
        $this->addSql('ALTER TABLE parcours_date CHANGE id_parcours id_parcours INT DEFAULT NULL, CHANGE id_type_trajet id_type_trajet INT DEFAULT NULL, CHANGE utilisation_velo utilisation_velo VARCHAR(255) DEFAULT \'NULL\', CHANGE commentaire commentaire VARCHAR(255) DEFAULT \'NULL\', CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated updated DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE salarie CHANGE id_ville id_ville INT DEFAULT NULL, CHANGE id_entreprise id_entreprise INT DEFAULT NULL, CHANGE id_service id_service INT DEFAULT NULL, CHANGE id_role id_role INT DEFAULT NULL, CHANGE distance distance NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE url_geovelo url_geovelo VARCHAR(255) DEFAULT \'NULL\', CHANGE created created DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE updated updated DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE type_trajet CHANGE coef_km coef_km DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ville CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE num_departement num_departement VARCHAR(3) DEFAULT \'NULL\', CHANGE actif actif TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX ville_code_commune ON villes_france_free');
        $this->addSql('ALTER TABLE villes_france_free CHANGE ville_departement ville_departement VARCHAR(3) DEFAULT \'NULL\', CHANGE ville_slug ville_slug VARCHAR(255) DEFAULT \'NULL\', CHANGE ville_nom ville_nom VARCHAR(45) DEFAULT \'NULL\', CHANGE ville_nom_simple ville_nom_simple VARCHAR(45) DEFAULT \'NULL\', CHANGE ville_nom_reel ville_nom_reel VARCHAR(45) DEFAULT \'NULL\', CHANGE ville_nom_soundex ville_nom_soundex VARCHAR(20) DEFAULT \'NULL\', CHANGE ville_nom_metaphone ville_nom_metaphone VARCHAR(22) DEFAULT \'NULL\', CHANGE ville_code_postal ville_code_postal VARCHAR(255) DEFAULT \'NULL\', CHANGE ville_commune ville_commune VARCHAR(3) DEFAULT \'NULL\', CHANGE ville_arrondissement ville_arrondissement SMALLINT UNSIGNED DEFAULT NULL, CHANGE ville_canton ville_canton VARCHAR(4) DEFAULT \'NULL\', CHANGE ville_amdi ville_amdi SMALLINT UNSIGNED DEFAULT NULL, CHANGE ville_population_2010 ville_population_2010 INT UNSIGNED DEFAULT NULL, CHANGE ville_population_1999 ville_population_1999 INT UNSIGNED DEFAULT NULL, CHANGE ville_population_2012 ville_population_2012 INT UNSIGNED DEFAULT NULL COMMENT \'approximatif\', CHANGE ville_densite_2010 ville_densite_2010 INT DEFAULT NULL, CHANGE ville_surface ville_surface DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ville_longitude_deg ville_longitude_deg DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ville_latitude_deg ville_latitude_deg DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ville_longitude_grd ville_longitude_grd VARCHAR(9) DEFAULT \'NULL\', CHANGE ville_latitude_grd ville_latitude_grd VARCHAR(8) DEFAULT \'NULL\', CHANGE ville_longitude_dms ville_longitude_dms VARCHAR(9) DEFAULT \'NULL\', CHANGE ville_latitude_dms ville_latitude_dms VARCHAR(8) DEFAULT \'NULL\', CHANGE ville_zmin ville_zmin INT DEFAULT NULL, CHANGE ville_zmax ville_zmax INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('ALTER TABLE declaration_honneur CHANGE url_geovelo url_geovelo VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE entreprise CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE mois CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE3109E0F43');
        $this->addSql('ALTER TABLE parcours CHANGE id_mois id_mois INT NOT NULL, CHANGE id_salarie id_salarie INT NOT NULL, CHANGE descript_trajet descript_trajet VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE nb_km_effectue nb_km_effectue NUMERIC(10, 2) DEFAULT NULL, CHANGE indemnisation indemnisation NUMERIC(10, 2) DEFAULT NULL, CHANGE cloture cloture TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE validation validation TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE commentaire commentaire VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX idx_99b1dee3109e0f43 ON parcours');
        $this->addSql('CREATE INDEX FK_type_trajet ON parcours (id_type_trajet)');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE3109E0F43 FOREIGN KEY (id_type_trajet) REFERENCES type_trajet (id)');
        $this->addSql('ALTER TABLE parcours_date CHANGE id_type_trajet id_type_trajet INT NOT NULL, CHANGE id_parcours id_parcours INT NOT NULL, CHANGE utilisation_velo utilisation_velo VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE commentaire commentaire VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE salarie CHANGE id_ville id_ville INT NOT NULL, CHANGE id_service id_service INT NOT NULL, CHANGE id_entreprise id_entreprise INT NOT NULL, CHANGE id_role id_role INT NOT NULL, CHANGE distance distance NUMERIC(10, 2) DEFAULT NULL, CHANGE url_geovelo url_geovelo VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE actif actif TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE type_trajet CHANGE coef_km coef_km DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ville CHANGE id id INT NOT NULL, CHANGE num_departement num_departement VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE actif actif TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE villes_france_free CHANGE ville_departement ville_departement VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_slug ville_slug VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_nom ville_nom VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_nom_simple ville_nom_simple VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_nom_reel ville_nom_reel VARCHAR(45) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_nom_soundex ville_nom_soundex VARCHAR(20) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_nom_metaphone ville_nom_metaphone VARCHAR(22) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_code_postal ville_code_postal VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_commune ville_commune VARCHAR(3) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_arrondissement ville_arrondissement SMALLINT UNSIGNED DEFAULT NULL, CHANGE ville_canton ville_canton VARCHAR(4) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_amdi ville_amdi SMALLINT UNSIGNED DEFAULT NULL, CHANGE ville_population_2010 ville_population_2010 INT UNSIGNED DEFAULT NULL, CHANGE ville_population_1999 ville_population_1999 INT UNSIGNED DEFAULT NULL, CHANGE ville_population_2012 ville_population_2012 INT UNSIGNED DEFAULT NULL COMMENT \'approximatif\', CHANGE ville_densite_2010 ville_densite_2010 INT DEFAULT NULL, CHANGE ville_surface ville_surface DOUBLE PRECISION DEFAULT NULL, CHANGE ville_longitude_deg ville_longitude_deg DOUBLE PRECISION DEFAULT NULL, CHANGE ville_latitude_deg ville_latitude_deg DOUBLE PRECISION DEFAULT NULL, CHANGE ville_longitude_grd ville_longitude_grd VARCHAR(9) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_latitude_grd ville_latitude_grd VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_longitude_dms ville_longitude_dms VARCHAR(9) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_latitude_dms ville_latitude_dms VARCHAR(8) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE ville_zmin ville_zmin INT DEFAULT NULL, CHANGE ville_zmax ville_zmax INT DEFAULT NULL');
        $this->addSql('CREATE INDEX ville_code_commune ON villes_france_free (ville_code_commune)');
    }
}
