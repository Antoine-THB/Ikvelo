<?php

namespace App\Controller;

use App\Entity\Parcours;
use App\Entity\Config;
use App\Libs\StatClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /*
     * Page d'accueil du site en admin permettant d'avoir
     * la liste des déplacement necessitant la validation
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        //récupération de la liste des déplacements non validés        
        $parcoursNV = $em->getRepository(Parcours::class)->listParcoursClotNonValid();
        
        //récupération de la liste des déplacements non clots par les salariés
        $parcoursNC = $em->getRepository(Parcours::class)->listParcoursNonClot();
        
        //recuperation  du mois de référence
        $moisRef = $em->getRepository(Config::class)->findOneByLibelle("mois_fin_periode");
        $moisRefInt = intval($moisRef->getValueNum());
        //var_dump($moisRef);
        
        //recupération des stat de l'année en cours
        //recuperation de l'année en cours
        $madate = new \DateTime();
        $an = $madate->format('Y');
        
        $objStat = new StatClass($em);
        $tabStat = $objStat->genereStatParcoursPourAn($an);
        
        //récupération des stats pour toutes les années
        $tabStatAn = $objStat->genereStatParcoursParAn($moisRefInt);
        
        return $this->render('BackOffice/default/index.html.twig', array(
            'parcoursNV'    => $parcoursNV,
            'parcoursNC'    => $parcoursNC,
            'tabStat'       => $tabStat,
            'tabStatAn'     => $tabStatAn,
            'moisRef'       => $moisRef->getValue(),
        ));
    }
    
    /*
     * Statistique des rembouresements par année par salarié
     * en fonction du mois de cloture
     */
    public function statClotAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        //recuperation  du mois de référence
        $moisRef = $em->getRepository('IsenBackOfficeBundle:Config')->findOneByLibelle("mois_fin_periode");
        $moisRefInt = intval($moisRef->getValueNum());
        
        $objStat = new StatClass($em);
        //récupération des stats pour toutes les années par salarié
        $tabStatAn = $objStat->genereStatParcoursParAnParSal($moisRefInt);
               
        
        return $this->render('default/stat.html.twig', array(            
            'tabStatAn'     => $tabStatAn,
            'title'         => "Remboursements annuels",
            'moisRef'       => $moisRef->getValue(),
        ));
    }
}
