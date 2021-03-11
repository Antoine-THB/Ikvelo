<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs;

use Doctrine\ORM\EntityManager;
use App\Entity\Parcours;

/**
 * Description of StatClass
 * founie les statistiques de km effectués et indemnisation remboursée
 * pour une année (obligatoire)
 * pour un salarié (optionnel)
 *
 * @author christophe
 */
class StatClass {
    private $em;
    private $userId;
    private $annee;
    private $mois; //mois d'initialisation 
    private $montantIndemTotal; //montant des indemnisations en cours total
    private $parcoursNbKmTotal; //nombre de km parcourus total
    private $tabResult; //tableau de résultat de la requete
    
    
    public function __construct(EntityManager $em=NULL,$userId=NULL, $annee=NULL, $mois=NULL)
    {
        $this->em                   = $em;
        $this->userId               = $userId;
        $this->annee                = $annee;
        $this->mois                 = $mois;
        $this->montantIndemTotal    = 0;
        $this->parcoursNbKmTotal    = 0;
        $this->tabResultParAn       = array();
        $this->tabResultPourAn      = array();
        $this->tabResultParAnSal    = array();
        $this->tabResultPourAnSal   = array();
        $this->tabResultMoy         = array();
        $this->tabResultMoySal      = array();
    }
    
    /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour toutes les années sans information sur le salarié
     * en prenant en compte le mois départ
     * 
     */
    public function genereStatParcoursParAn($moisRefInt){
        $this->tabResultParAn = $this->em->getRepository(Parcours::class)->getStatToutAn($moisRefInt);
        return $this->tabResultParAn;
    }
    
    /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour toutes les années par salarié
     * en prenant en compte le mois départ
     * 
     */
    public function genereStatParcoursParAnParSal($moisRefInt){
        $this->tabResultParAn = $this->em->getRepository(Parcours::class)->getStatToutAnSal($moisRefInt);
        return $this->tabResultParAn;
    }
    
    /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour une année particulière sans information sur le salarié
     * 
     */
    public function genereStatParcoursPourAn($annee=NULL){
        $this->tabResultPourAn = $this->em->getRepository(Parcours::class)->getStatAn($annee);
        return $this->tabResultPourAn;
    }
    
    /*  STAT SALARIE */
     /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour toutes les années pour un salarié
     * 
     */
    public function genereStatParcoursParAnSal($userId=NULL){
        $this->tabResultParAnSal = $this->em->getRepository(Parcours::class)->getStatAnSalSsMois($annee=NULL,$userId);
        return $this->tabResultParAnSal;
    }
    
     /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour toutes les années pour un salarié en prenant en compte
     * le mois init
     * 
     */
    public function genereStatParcoursParAnSalMoisInit($userId=NULL,$moisRefInt){
        
        //mois init annee A
        $mois_deb= $moisRefInt;
        //$mois_deb= $mois_init["value_num "];
        
        //mois fin annee A+1
        $mois_fin = $mois_deb +1;
        
        //récupérattion de la liste des annees
        $ans = $this->em->getRepository(Parcours::class)->getListAn();
        //var_dump($ans);
        
        //parcours des années pour recuperer la liste des stat
        /*
        foreach ($ans as $key => $tabAn) {
            //$this->tabResultParAnSal[$tabAn['annee']] = $this->em->getRepository('IsenBackOfficeBundle:Parcours')
              //                              ->getStatAnSalSsMoisMsInit($tabAn['annee'],$userId, $mois_deb, $mois_fin);
            array_push($this->tabResultParAnSal, $this->em->getRepository('IsenBackOfficeBundle:Parcours')
                                            ->getStatAnSalSsMoisMsInit($tabAn['annee'],$userId, $mois_deb, $mois_fin));
        }
        */
        $this->tabResultParAnSal = $this->em->getRepository(Parcours::class)->getStatAnSalSsMoisMsInit($userId, $mois_deb, $mois_fin);
        
        //$this->tabResultParAnSal = $this->em->getRepository('IsenBackOfficeBundle:Parcours')
          //                                  ->getStatAnSalSsMoisMsInit($annee=NULL,$userId, $mois_deb, $mois_fin);
        
        return $this->tabResultParAnSal;
    }
    
    /*
     * recupère les stats de parcours de nb de kilometres et indemnisation
     * pour une année particulière pour un salarié
     * 
     */
    public function genereStatParcoursPourAnSal($annee=NULL,$userId=NULL){
        $this->tabResultPourAnSal = $this->em->getRepository(Parcours::class)->getStatAnSal($annee,$userId);
        return $this->tabResultPourAnSal;
    }
    
    /*
     * recupère les stats moyenne de nb de kilometres et indemnisation
     * pour un salarié
     * 
     */
    public function genereStatMoySal($userId=NULL){
        $this->tabResultMoySal = $this->em->getRepository(Parcours::class)->getStatMoyAnSal($userId);
        return $this->tabResultMoySal;
    }
    
    /*
     * recupère les stats moyenne de nb de kilometres et indemnisation
     * pour tous les salariés 
     * 
     */
    public function genereStatMoy(){
        $this->tabResultMoy = $this->em->getRepository(Parcours::class)->getStatMoyAn();
        return $this->tabResultMoy;
    }
}
