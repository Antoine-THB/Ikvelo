<?php
namespace App\Libs;

use App\Entity\Service;
use App\Entity\Salarie;
use App\Entity\DeclarationHonneur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author christophe
 */
class Tools extends Controller {
    
    private $em;

    public function __construct(EntityManager $em=NULL)
    {
        $this->em = $em;
    }
    
    /*
     * Fonction permettant de récupérer le salarie et
     * sa declaration sur l'honneur.
     * Si la déclaration n'est pas enregistrée redirection vers 
     * une page d'erreur demandant à compléter le profil
     */
    public function getSalarieDeclaration(Salarie $salarie)
    {
        //recupération du user loggé
        //$user = $this->getUser();
        
        //récupération du salarié
        //$salarie = $user->getSalarie();
        $declarationHonneur = null;
        
        //$declarationHonneur = $user->getDeclarationHonneur();
        //$declarationHonneur = $this->getDeclarationHonneur($salarie);
        
        if(is_null($declarationHonneur)){
            //return $this->render('FrontOffice/default/profilErreur.html.twig',array(
                //'salarie'       => $salarie,
                //'declaration'   => $declarationHonneur,
            //    ));
            return $this->redirectToRoute('isen_front_profil');
        }
        
        return 'OK';
    }
    
    /**
     * Ramene un slarie à partir de son username CAS.
     */
    public function getSalarie($username)
    {
        $salarie = $this->em->getRepository(Salarie::class)
                                ->findOneByCasUsername($username);
        
        return $salarie;
    }

    /**
     * Récupère tous les services
     */
    public function getServices()
    {
 
        $services = $this->em->getRepository(Service::class)->findAll();
        
        return $services;
    }
    /**
     * Ramene une declaration sur l'honneur active pour le salarie.
     */
    public function getDeclarationHonneur($salarie)
    {
        $declaration = $this->em->getRepository(DeclarationHonneur::class)
                    ->findOneBy([
                        'idSalarie' => $salarie,
                        'actif'     => true
                ]);
        
        return $declaration;
    }
    
    /*
     * Ramene un objet moisAnnee permettant de peupler un combolist des jours
     * de ce mois et cette annee
     * ARG:
     *      $typeMois : varchar : indique le type de l'ragument $mois 
     *                  (i => mois au format numérique 'ex : 4)
     *                   c => mois au format caracetre (ex: avril)
     *      $mois : mixed => valeur du mois (numérique ou caractere)
     *      $annee : int => valeur de l'année
     * 
     * RETOUR:
     *      $tabJourMois : array => tableau des jours du mois avec nom du jour et numéro
     */
    public function getJour4MoisAnnee($typeMois,$mois,$annee){
        $valMois = null;
        $tabJourMois = array();
        
        if($typeMois == 'c'){//traitement du mois pour le rendre numérique
            switch ($mois){
                case 'janvier':
                    $valMois = 1;
                    break;
                case ('février' || 'fevrier'):
                    $valMois = 2;
                    break;
                case 'mars':
                    $valMois = 3;
                    break;
                case 'avril':
                    $valMois = 4;
                    break;
                case 'mai':
                    $valMois = 5;
                    break;
                case 'juin':
                    $valMois = 6;
                    break;
                case 'juillet':
                    $valMois = 7;
                    break;
                case ('aout' || 'août'):
                    $valMois = 8;
                    break;
                case 'septembre':
                    $valMois = 9;
                    break;
                case 'octobre':
                    $valMois = 10;
                    break;
                case 'novembre':
                    $valMois = 11;
                    break;
                case ('décembre' || 'decembre'):
                    $valMois = 12 ;
                    break;
            }
        }else{
            $valMois = $mois;
        }
        
        setlocale(LC_ALL, 'fr_FR');
        //calcul du nombre de jour dans le mois
        $nbJour = date("t",mktime( 0, 0, 0, $valMois, 1, $annee ));
        
        //genere un tableau des jours du mois
        setlocale(LC_ALL, 'fr_FR');
        for($i=1; $i<=$nbJour; $i++){
            $numJour = date("d",mktime( 0, 0, 0, $valMois, $i, $annee ));
            $nomJour = $this->getFrenchDayName(date("D",mktime( 0, 0, 0, $valMois, $i, $annee )));
            //$tabJourMois[$i] = $nomJour." - ".$numJour;
            $tabJourMois[$nomJour." - ".$numJour] = $i;
        }
        
        return $tabJourMois;
    }
    
    public function getJourName4MoisAnnee($jour,$mois,$annee){
        $nomJour = "";
        $nomJour = date("D",mktime( 0, 0, 0, $mois, $jour, $annee ));
        return $nomJour;
    }
    
    public function getFrenchDayName($dayName) {
        if($dayName){
            switch ($dayName){
                    case 'Mon':
                        $nomJour = 'lundi';
                        break;
                    case ('Tue'):
                        $nomJour = 'mardi';
                        break;
                    case 'Wed':
                        $nomJour = 'mercredi';
                        break;
                    case 'Thu':
                        $nomJour = 'jeudi';
                        break;
                    case 'Fri':
                        $nomJour = 'vendredi';
                        break;
                    case 'Sat':
                        $nomJour = 'samedi';
                        break;
                    case 'Sun':
                        $nomJour = 'dimanche';
                        break;
                    
                }
                return $nomJour;
                
            }else{
                return NULL;
            }
    }
    
    /*
     * Ramène l'année courante asinsi que l'année précédente et l'année suivante
     * dans l'ordre suivant N-1, N, N+1
     */
    public function get3Annee(){
        
        //recuperation de l'année en cours
        $madate = new \DateTime();
        $an = $madate->format('Y');
        $anMoinsUn = $an - 1;
        $anPlusUn = $an + 1;
        
        $tabAn = array(
           '0'    => $anMoinsUn,
           '1'    => $an,
           '2'    => $anPlusUn,
        );
        
        return $tabAn;
    }
    
    /*
     * Ramène l'année courante asinsi que les deux années précédentes
     * dans l'ordre suivant  N, N-1,N-2
     */
    public function get3AnneeOld(){
        
        //recuperation de l'année en cours
        $madate = new \DateTime();
        $an = $madate->format('Y');
        $anMoinsUn = $an - 1;
        $anMoinsDeux = $an - 2;
        
        $tabAn = array(
           '0'    => $an,
           '1'    => $anMoinsUn,
           '2'    => $anMoinsDeux,
        );
        
        return $tabAn;
    }
}
