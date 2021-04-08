<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs;

use App\Entity\Config;
use App\Entity\Salarie;
use App\Entity\DeclarationHonneur;
use App\Entity\ParcoursDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

/**
 * Description of CalculIndemClass
 *
 * @author christophe
 */
class CalculIndemClass {
    private $em;
    private $userId;
    private $annee;
    private $message;
    private $montantRestant; //montant restant pour l'année
    private $montantIndemEnCours; //montant des indemnisations en cours
    private $parcoursNbKm; //nombre de km parcourus

    public function __construct(EntityManager $em=NULL,$userId, $annee)
    {
        $this->em                   = $em;
        $this->userId               = $userId;
        $this->annee                = $annee;
        $this->message              = "";
        $this->montantRestant       = 0;
        $this->montantIndemEnCours  = 0;
        $this->parcoursNbKm         = 0;
        
        //calcul de la situation du salarié
        $this->recupSituationPlafond();
    }
    
    /*
     * calcul de la situation du salarié par rapport au flafond 
     * pour une année donnée
     */
    public function recupSituationPlafond() {
        //récupération du plafond
        $plafondObj = $this->em->getRepository(Config::class)->findOneByLibelle('plafond');
        $valPlafond = $plafondObj->getValueNum();
                
        //récupération de la limite avant plafond
        $plafondAlrtObj = $this->em->getRepository(Config::class)->findOneByLibelle('plafond-alerte');
        $valPlafondAlrt = $plafondAlrtObj->getValueNum();

        
        //recupération du montant des indemnisation acquis ou en cours
         $this->montantIndemEnCours = $this->em->getRepository(ParcoursDate::class)->statParcoursUserAnIndemnisation($this->userId,$this->annee);
        
        //calcul du montant restant pour le salarié
        if( $this->montantIndemEnCours >= $valPlafond ){
            $this->montantRestant    = 0;
        }else{
            $this->montantRestant = $valPlafond-$this->montantIndemEnCours;
        }
        
        
        if( $this->montantIndemEnCours + $valPlafondAlrt >= $valPlafond) {
            $this->message = "Attention, vous arrivez à la limite de vos indemnisations.\n";
            $this->message .= "Il vous reste ".$this->montantRestant."€ pour l'année ".$this->annee."\n\r";
        }
        
        //recuperation du nombre de km parcourus
        $this->parcoursNbKm = $this->em->getRepository(ParcoursDate::Class)->statParcoursUserAnNbKm($this->userId,$this->annee);
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function getMontantRestant() {
        return $this->montantRestant;
    }
    
    public function getMontantIndemEnCours() {
        return $this->montantIndemEnCours;
    }
    
    public function getParcoursNbKm() {
        return $this->parcoursNbKm;
    }
}
