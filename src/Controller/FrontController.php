<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Libs\CalculIndemClass as LibsCalculIndemClass;
use App\Libs\StatClass as LibsStatClass;
use App\Entity\Parcours;
use App\Entity\ParcoursDate;
use App\Entity\Salarie;
use App\Entity\DeclarationHonneur;
use App\Entity\Config;
use App\Entity\Mois;
use App\Entity\RemboursementAbonnement;
use App\Entity\TypeTrajet;
use App\Form\ParcoursDateFrontType;
use App\Form\ParcoursFrontType;
use App\Form\RechercheVilleType;
use App\Form\SalarieFrontType;
use App\Libs\CalculIndemTCClass;
use App\Libs\Tools;
use App\Repository\ParcoursDateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Front controller.
 */
class FrontController extends AbstractController
{
    /*
     * Verification de la présence de la déclaration sur l'honneur du salarié
     */
    private function verifUser($user){
        //$tabUser = array();
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //var_dump($salarie);
        
        //$outil = new \Isen\BackOfficeBundle\Libs\Tools();
        //$madeclaration = $outil->getDeclarationHonneur($salarie);
        $declaration = null;
        
        
        
        
        $em = $this->getDoctrine()->getManager();
        $declaration = $em->getRepository(DeclarationHonneur::class)
                ->findOneBy([
                    'idSalarie' => $salarie,
                    'actif'     => true
                ]);
        
        //if(ev)
        //var_dump(is_null($declaration));
        
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }else{
            return 'OK';
        }
        
        //$tabUser = [$user,$salarie,$declaration];
        
        return $declaration;
        
    }

    /**
     * @Route("/", name="front_index")
     * 
     */
    public function indexAction()
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        // $em = $this->getDoctrine()->getManager();
        // //var_dump($user);
        
        // if($user->getUsername() == 'admin')
        // {
        //     $salarie = $em->getRepository(Salarie::class)
        //                 ->find(1);
        //     $user->setId(1);
        // }else{
        //     $salarie = $em->getRepository(Salarie::class)
        //                 ->find(2);
        //     $user->setId(2);
        // }
        
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        
        
        //recuperation de l'année en cours
        $madate = new \DateTime();
        $an = $madate->format('Y');
        
        //récupération des stats pour l'année en cours
        $em = $this->getDoctrine()->getManager();
        //liste des parcours non clots
        $parcoursNC = $em->getRepository(Parcours::class)->listParcoursUserNonClotAn($user->getId(),$an);
        
        //liste des parcours non validés
        $parcoursNV = $em->getRepository(Parcours::class)->listParcoursUserNonValidAn($user->getId(),$an);
        
        //liste des parcours validés
        $parcoursV = $em->getRepository(Parcours::class)->listParcoursUserValidAn($user->getId(),$an);

        //Récupération des abonnements Validés
        $abonnementV = $em->getRepository(Abonnement::class)->AboValid($user->getId());
        $messageAvertissement = null;
        foreach($abonnementV as $abo){
            //condition pour mettre un message si l'abonnement se termine bientot
            $value = $abo->getDateDebut()->diff($abo->getDateFin())->format('%a');
            if (intval($value)<=14)
            $messageAvertissement = "Votre abonnement se termine dans ".$value." jours.";

            $nbrRemboursement = $em->getRepository(RemboursementAbonnement::class)->nbrRemboursement($abo->getId());
            $nbrMoisAbonnement = intval($abo->getDateDebut()->diff($abo->getDateFin())->format('%m'))+1;
            $nbrMoisRestantAbonnemnt = intval($madate->diff($abo->getDateFin())->format('%m'))+1;
            
        }
        //Récupération des abonnements Non Validés
        $abonnementNV = $em->getRepository(Abonnement::class)->AboNonValid($user->getId());
        
        //boucle qui parcourt les abonnnements
        //Récupère le dernier remboursement, si un mois s'est écoulé nouveau remboursement 
        //vérification si plafond n'est pas atteint
        



        //récupération des statistiques pour l'année en cours
        $objStat = new LibsStatClass($em);
        $tabStat = $objStat->genereStatParcoursPourAnSal($an,$user->getId());
        //création des tableau pour l'affichage dans chartjs
        $tabMois    = array();
        $tabKm      = array(0,0,0,0,0,0,0,0,0,0,0,0);
        $tabIndem   = array(0,0,0,0,0,0,0,0,0,0,0,0);
 
        foreach ($tabStat as $tabUniq) {
            //$listMois .= ",".$tabUniq['mois'];
            $tabMois[]    = $tabUniq['mois'];
            $tabKm[$tabUniq['moisid']-1]      = $tabUniq['nbKmEffectue'];
            $tabIndem[$tabUniq['moisid']-1]   = $tabUniq['totIndemnisation'];

        }
        //$listMois = implode(",",$tabMois);
        $listMois   = implode(",",$tabMois);
        $listKm     = implode(",",$tabKm);
        $listIndem  = implode(",",$tabIndem);
        
        //récupération des stat moyennes
        $tabMoyStat = $objStat->genereStatMoySal($user->getId());

        $tabMoyKm      = array(0,0,0,0,0,0,0,0,0,0,0,0);
        $tabMoyIndem   = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($tabMoyStat as $tabMoyUniq) {

            $tabMoyKm[$tabMoyUniq['moisid']-1]      = $tabMoyUniq['moyNbKmEffectue'];
            $tabMoyIndem[$tabMoyUniq['moisid']-1]   = $tabMoyUniq['moyIndemnisation'];

        }

        $listMoyKm     = implode(",",$tabMoyKm);
        $listMoyIndem  = implode(",",$tabMoyIndem);
        
        //recuperation de la situation du salarié par rapport au plafond
        $situationSalarie = new LibsCalculIndemClass($em,$user->getId(),$an);
        $situationSalarieTC = new CalculIndemTCClass($em,$user->getId(),$an);

        //bilan des remboursements "comptables" en fonction du mois de réference
        //recuperation  du mois de référence
        $moisRef = $em->getRepository(Config::class)->findOneByLibelle("mois_fin_periode");
        $moisRefInt = intval($moisRef->getValueNum());

        $tabStat = $objStat->genereStatParcoursParAnSalMoisInit($salarie,$moisRefInt);
        
        return $this->render('front/index.html.twig',array(
            'user'                      => $user,
            'parcoursNC'                => $parcoursNC,
            'parcoursNV'                => $parcoursNV,
            'parcoursV'                 => $parcoursV,
            'an'                        => $an,
            'parcoursNbKm'              => $situationSalarie->getParcoursNbKm(),
            //'parcoursIndemnisation'     => $parcoursIndemnisation,
            'parcoursIndemnisation'     => $situationSalarie->getMontantIndemEnCours(),
            'montantRestant'            => $situationSalarieTC->getMontantRestant(),
            'montantRestantTC'            => $situationSalarie->getMontantRestant(),
            'tabStat'                   => $tabStat,
            'tabMois'                   => $tabMois,
            'tabKm'                     => $tabKm,
            'tabIndem'                  => $tabIndem,
            'listMois'                  => $listMois,
            'listKm'                    => $listKm,
            'listIndem'                 => $listIndem,
            'listMoyKm'                 => $listMoyKm,
            'listMoyIndem'              => $listMoyIndem,
            'tabStatAn'                 => $tabStat,
            'moisRef'                   => $moisRef->getValue(),
            'abonnementV'               => $abonnementV,
            'abonnementNV'              => $abonnementNV,
            'messageAvertissement'      => $messageAvertissement,
            ));
    }
    
    /**
     * @Route("/profil", name="front_profil")
     * 
     */
    public function profilAction()
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //recupération de la déclaration sur l'honneur associée (active)
        //$declaration = new DeclarationHonneur();
        $em = $this->getDoctrine()->getManager();


        $declaration = $em->getRepository(DeclarationHonneur::class)
                ->findOneBy([
                    'idSalarie' => $salarie,
                    'actif'     => true
                ]);
        
        return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
    }
    
    /**
     * @Route("/deplacement/list", name="front_parcours")
     * 
     */
    public function parcoursListAction()
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        //$parcours = $em->getRepository('IsenBackOfficeBundle:Parcours')->findByIdSalarie($user->getId());
        $parcours = $em->getRepository(Parcours::class)->findBy(array('idSalarie' => $user->getId()),
                array('annee' => 'DESC', 'idMois' => 'DESC')
                );

        
        
        return $this->render('front/parcoursList.html.twig',array(
            'parcours' => $parcours,
            'user'=>$user,
                ));
    }

    /**
     * @Route("/mensuel/new", name="front_new_mensuel")
     * 
     */
    public function mensuelNewAction(Request $request)
    {
        // var_dump($request);
        //Le temps qu'on affiche le commentaire 
        //cette année         
        $maNewdate = new \DateTime();
        $cetteAnnee = $maNewdate->format('Y');

        //message
        $monmessage = null;

        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //verification de la présence de la déclaration sur l'honneur
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }

        
        $parcour = new Parcours();
        
        //insertion de la distnce de base
        $parcour->setDistanceBase($salarie->getDistance());
        
        //vérification que le plafond n'a pas deja été atteind ou que la limite
        $em = $this->getDoctrine()->getManager();
                     
        //recuperation de la situation du salarié par rapport au plafond
        $situationSalarie = new LibsCalculIndemClass($em,$user->getId(),$cetteAnnee);
        
        //message dans description du trajet
        $parcour->setDescriptTrajet('Domicile - travail');
        
        //préselection du mois en cours
        //mois en cours
        $madate = new \DateTime();
        $cemois = $madate->format('n');
        $moisEnCours = $em->getRepository(Mois::class)->find($cemois);
        $parcour->setIdMois($moisEnCours);
        
        $form = $this->createForm(ParcoursFrontType::class, $parcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //vérification que le mois n'a pas déja été utilisé            
            $monparcours = $form->getData();
            
            //récupération de l'année et du mois
            $an = $monparcours->getAnnee();
            $mois = $monparcours->getIdMois()->getId();
            
            //verification que le mois n'est pas deja utilisé
            $parcoursAnMois = $em->getRepository(Parcours::class)->listParcoursUserAnMois($mois, $an, $user->getId()); 
            $nbOccurence = count($parcoursAnMois);
            if($nbOccurence != 0){
                $monmessage .= '!! Attention ce mois a déjà été utilisé '.$nbOccurence.' fois !!';
                return $this->render('front/newFormMois.html.twig', array(
                    'parcour'       => $parcour,
                    'form'          => $form->createView(),
                    'monmessage'    => $monmessage,
                ));
            }
            
            $parcour->setIdSalarie($salarie);
            $parcour->setDateCreation(new \DateTime());
            $parcour->setCreated(new \DateTime());
            $parcour->setUpdated(new \DateTime());
            //$em = $this->getDoctrine()->getManager();
            $em->persist($parcour);
            $em->flush();

            return $this->redirectToRoute('front_mensuel_detail', array('id' => $parcour->getId()));
        }

        return $this->render('front/newFormMois.html.twig', array(
            'parcour'       => $parcour,
            'form'          => $form->createView(),
            'monmessage'    => $situationSalarie->getMessage(),
        ));
        
        
    }
    

    /**
     * @Route("/mensuel/show/{id}", name="front_mensuel_detail")
     * 
     */
    public function mensuelShowAction($id)
    {
        //message
        $monmessage = null;

        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        $parcour = $em->getRepository(Parcours::class)->find($id);

        //$parcourJours = $em->getRepository('IsenBackOfficeBundle:ParcoursDate')->findByIdParcours($id);
        $parcourJours = $em->getRepository(ParcoursDate::class)->findBy(
            ['idParcours'    => $id],
            ['jourNum'        =>'ASC']
                );
         //dump($parcourJours);
        
        $annee = $parcour->getAnnee();
        
        //recuperation de la situation du salarié par rapport au plafond
        $situationSalarie = new LibsCalculIndemClass($em,$user->getId(),$annee);
        
        return $this->render('front/parcoursShow.html.twig',array(
            'parcour'       => $parcour,
            'parcourJours'  => $parcourJours,
            'user'          => $user,
            'test'          => 'test',
            'monmessage'    => $situationSalarie->getMessage()
                ));
    }
    
    /**
     * cloture d'un déplacement
     *
     * @Route("/mensuel/clot/{id}", name="front_clot_dep_jour")
     */
    public function mensuelClotAction($id)
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        $parcour = $em->getRepository(Parcours::class)->find($id);

        $parcourJours = $em->getRepository(ParcoursDate::class)->findByIdParcours($id);
         //dump($parcourJours);
        
        //modification du Parcours pour cloture
        $parcour->setCloture(1);
        
        $em->persist($parcour);
            
        $em->flush();
        
        return $this->render('front/parcoursShow.html.twig',array(
            'parcour'   => $parcour,
            'parcourJours'  => $parcourJours,
            'user'      => $user,
            'test'      => 'test'
                ));
    }
    
    /**
     * fiche complete pour impression
     *
     * @Route("/mensuel/print/{id}", name="front_mensuel_print")
     */
    public function mensuelPrintAction($id)
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        $parcour = $em->getRepository(Parcours::class)->find($id);

        $parcourJours = $em->getRepository(ParcoursDate::class)->findByIdParcours($id);
         //dump($parcourJours);
        
        return $this->render('front/parcoursPrint.html.twig',array(
            'parcour'       => $parcour,
            'parcourJours'  => $parcourJours,
            'user'          => $user,
            'salarie'       => $salarie,
            'test'          => 'test'
                ));
    }
    
    /**
     * liste des parcours journaliers effectués
     * @Route("/mensuel/jour/list", name="front_list_dep_jour")
     */
    public function jourListAction()
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        $parcours = $em->getRepository(Parcours::class)->findByIdSalarie($user->getId());

                
        return $this->render('front/parcoursList.html.twig',array(
            'parcours' => $parcours,
            'user'=>$user,
                ));
    }
    
    /**
     * Nouveau parcours journaliers effectués
     * @Route("/mensuel/jour/new/{id}", name="front_new_dep_jour")
     */
    public function jourNewAction($id,Request $request)
    {
        //message
        $monmessage = null;
        
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //verification de la présence de la déclaration sur l'honneur
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }

        
        //récupération du parcours
        $em = $this->getDoctrine()->getManager();
        $parcours = $em->getRepository(Parcours::class)->find($id);
        
        //récupération de l'année et du mois
        $annee = $parcours->getAnnee();
        $mois = $parcours->getIdMois()->getId();
        $moisLibel = $parcours->getIdMois()->getMois();
        
        //recuperation de la situation du salarié par rapport au plafond total
        $situationSalarieTotal = new CalculIndemTCClass($em,$user->getId(),$annee);
        //recuperation de la situation du salarié par rapport au plafond transport en commun
        $situationSalarieTC = new CalculIndemTCClass($em,$user->getId(),$annee);

        //recupertaion du parametre de coef
        // $config = $em->getRepository(Config::class)->findOneByLibelle('coef_km');
        $config = $em->getRepository(TypeTrajet::class)->findOneByLibelle('Vélo')->getCoef();
        
        $parcoursDate = new Parcoursdate();
        $parcoursDate->setIdParcours($parcours);
        $parcoursDate->setNbKmEffectue($salarie->getDistance()*2);
        
        //calcul du montant d'indemnisation possible pour le salarié
        $indemnisationPossible = $salarie->getDistance()*2*$config;
        //condition pour savoir si le salarié travail plus de 50%
        if($salarie->getTpsTravail()<=49){
            $indemnisationPossible = $indemnisationPossible*$salarie->getTpsTravail()/100;
        }
        //Condition si disponible TC
        if($indemnisationPossible<$situationSalarieTC->getMontantRestant()){
            //condition si dispo total
            if($indemnisationPossible<$situationSalarieTotal->getMontantRestant()){
                $parcoursDate->setIndemnisation($indemnisationPossible); 
            }
            else{
                $parcoursDate->setIndemnisation($situationSalarieTotal->getMontantRestant()); 
            }
        }
        else{
            if($situationSalarieTC->getMontantRestant()<$situationSalarieTotal->getMontantRestant()){
                $parcoursDate->setIndemnisation($situationSalarieTC->getMontantRestant()); 
                }
                else{
                    $parcoursDate->setIndemnisation($situationSalarieTotal->getMontantRestant()); 
                }
        }

        
        
        //selection du jour du mois
        $madate = new \DateTime();
        $cejour = $madate->format('j');
        $parcoursDate->setJourNum($cejour);
        
        
        
        
        
        $form = $this->createForm(ParcoursDateFrontType::class, 
                $parcoursDate,
                array('annee'       => $parcours->getAnnee(),
                        'mois'      => $parcours->getIdMois()->getId(),
                        'nbkminit'  => $salarie->getDistance(),
                        'coef'      => $config
                    )) ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $monparcours = $form->getData();
            
            //récupére le nom du jour
            $outil = new Tools();
            $parcoursDate->setJourLabel($outil->getJourName4MoisAnnee($monparcours->getJourNum(), $mois, $annee));
            
            $parcoursDate->setCreated(new \DateTime());
            $parcoursDate->setUpdated(new \DateTime());
            
            //met à jour l'indemnisation en fonction du nombre de km saisi
            //calcul du montant d'indemnisation possible pour le salarié
            // $indemnisationPossible = $monparcours->getNbKmEffectue()*$config;
            $indemnisationPossible = $monparcours->getNbKmEffectue()*$em->getRepository(TypeTrajet::class)->findOneById($monparcours->getidTypeTrajet())->getCoef();
            //condition pour savoir si le salarié travail plus de 50%
            if($salarie->getTpsTravail()<=49){
                $indemnisationPossible = $indemnisationPossible*$salarie->getTpsTravail()/100;
            }
            //Condition si disponible TC
            if($indemnisationPossible<$situationSalarieTC->getMontantRestant()){
                //condition si dispo total
                if($indemnisationPossible<$situationSalarieTotal->getMontantRestant()){
                    $parcoursDate->setIndemnisation($indemnisationPossible); 
                }
                else{
                    $parcoursDate->setIndemnisation($situationSalarieTotal->getMontantRestant()); 
                }
            }
            else{
                if($situationSalarieTC->getMontantRestant()<$situationSalarieTotal->getMontantRestant()){
                    $parcoursDate->setIndemnisation($situationSalarieTC->getMontantRestant()); 
                    }
                    else{
                        $parcoursDate->setIndemnisation($situationSalarieTotal->getMontantRestant()); 
                    }
            }
            //$parcoursDate->setIndemnisation($monparcours->getNbKmEffectue()*$config->getValueNum());
            
            $em->persist($parcoursDate);            
            $em->flush();
            
            //recuperation des infos de nb km et indemnite pour le parcours pour mise à jour
            $infoKmIndem = $em->getRepository(ParcoursDate::class)->recupParcoursDate4Parcours($parcoursDate->getIdParcours($id)->getId());
                        
            //mise à jour du parcours correspondant
            $parcours->setNbKmEffectue($infoKmIndem[0]['nbKmEffectue']);
            $parcours->setIndemnisation($infoKmIndem[0]['totIndemnisation']);
            $em->persist($parcours);
            
            $em->flush();

            return $this->redirectToRoute('front_mensuel_detail', array('id' => $id));
        }

        return $this->render('front/newDepJour.html.twig', array(
            'parcoursDate'  => $parcoursDate,
            'form'          => $form->createView(),
            'annee'         => $annee,
            'mois'          => $moisLibel,
            'parcoursId'    => $id,
            'titre'         => 'Nouveau',
            'delete_form'   => null,
            'monmessage'    => $situationSalarieTotal->getMessage()
        ));
    }
    
    /**
     * Edition parcours journaliers effectués
     * @Route("/mensuel/jour/edit/{id}", name="front_edit_dep_jour")
     */
    public function jourEditAction($id,Request $request)
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }                

        
        $em = $this->getDoctrine()->getManager();
        $parcoursDate = $em->getRepository(ParcoursDate::class)->find($id);
        
        $parcours = $em->getRepository(Parcours::class)->find($parcoursDate->getIdParcours($id)->getId());
        
        //recupertaion du parametre de coef
        $config = $em->getRepository(Config::class)->findOneByLibelle('coef_km');
        
        //récupération de l'année et du mois
        $annee = $parcours->getAnnee();
        $mois = $parcours->getIdMois()->getId();
        $moisLibel = $parcours->getIdMois()->getMois();
        
        $deleteForm = $this->createParcoursDateDeleteForm($parcoursDate);
        
        $form = $this->createForm(ParcoursDateFrontType::class, 
                $parcoursDate,
                array('annee'       => $parcours->getAnnee(),
                        'mois'      => $parcours->getIdMois()->getId(),
                        'nbkminit'  => $salarie->getDistance(),
                        'coef'      => $config->getValueNum()
                    )) ;
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $monparcours = $form->getData();
            
            //récupére le nom du jour
            $outil = new Tools();
            $parcoursDate->setJourLabel($outil->getJourName4MoisAnnee($monparcours->getJourNum(), $mois, $annee));
                        
            $parcoursDate->setUpdated(new \DateTime());
            
            //met à jour l'indemnisation en fonction du nombre de km saisi
            $parcoursDate->setIndemnisation($monparcours->getNbKmEffectue()*$config->getValueNum());
            
            $em->persist($parcoursDate);
            $em->flush();
            
            //recuperation des infos de nb km et indemnite pour le parcours pour miose à jour
            $infoKmIndem = $em->getRepository(ParcoursDateRepository::class)->recupParcoursDate4Parcours($parcoursDate->getIdParcours($id)->getId());

            
            //mise à jour du parcours correspondant
            $parcours->setNbKmEffectue($infoKmIndem[0]['nbKmEffectue']);
            $parcours->setIndemnisation($infoKmIndem[0]['totIndemnisation']);
            
            $em->persist($parcours);
            
            $em->flush();

            return $this->redirectToRoute('front_mensuel_detail', array('id' => $parcoursDate->getIdParcours($id)->getId()));
        }
        
        return $this->render('front/newDepJour.html.twig', array(
            'parcoursDate'  => $parcoursDate,
            'form'          => $form->createView(),
            'annee'         => $annee,
            'mois'          => $moisLibel,
            'parcoursId'    => $parcoursDate->getIdParcours($id)->getId(),
            'titre'         => 'Edition',
            'delete_form' => $deleteForm->createView(),
        ));
        
    }
    
    /**
     * modification du profil
     *
     * @Route("/profil/edit", name="front_profil_edit")
     * 
     */
    public function profilEditAction(Request $request)
    {
        //recupération du user loggé
        $user = $this->getUser();
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //recuperation de la declaration
        $em = $this->getDoctrine()->getManager();
        $declaration = $em->getRepository(DeclarationHonneur::class)
                ->findOneBy([
                    'idSalarie' => $salarie,
                    'actif'     => true
                ]);
        
        //recupertaion du parametre de coef
        $config = $em->getRepository(Config::class)->findOneByLibelle('plafond');
        
        $editForm = $this->createForm(SalarieFrontType::class, $salarie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $salarie->setUpdated(new \DateTime());
            //$this->getDoctrine()->getManager()->flush();
            $em->persist($salarie);
            $em->flush();
            
            //si le salarie modifie son profil, la declaration est regeneree
            //si la declaration existe deja, on la rend inactive
            if($declaration){
                $declaration->setActif(false);
                $em->persist($declaration);
                $em->flush();
            }
            //nouvelle declaration
            $declarationNew = new DeclarationHonneur();
            $declarationNew->setIdSalarie($salarie);
            $declarationNew->setActif(true);
            $declarationNew->setDistance($salarie->getDistance());
            $declarationNew->setIdEntreprise($salarie->getIdEntreprise());
            $declarationNew->setIdVille($salarie->getIdVille());
            $declarationNew->setPrime($config->getValueNum());
            $declarationNew->setAdresse($salarie->getAdresse());
            $declarationNew->setUrlGeovelo($salarie->getUrlGeovelo());
            $declarationNew->setCreated(new \DateTime());
            $declarationNew->setUpdated(new \DateTime());
            
            $em->persist($declarationNew);
            $em->flush();

            return $this->redirectToRoute('front_profil', array('id' => $salarie->getId()));
        }

        return $this->render('front/salarieEdit.html.twig', array(
            'salarie' => $salarie,
            'edit_form' => $editForm->createView(),
        ));
    }
    
    /**
     * recherche d'une ville
     * @Route("/ville/search", name="front_ville_search")
     *
     */
    public function villeSearchAction(Request $request)
    {
        //recupération du user loggé
        $user = $this->getUser();
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        //nombre d'éléments récupérés
        $nbResult = 10;
        
        $villes = NULL;
        $form = $this->createForm(RechercheVilleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $var = $form->get('ville')->getData();
            
            $em = $this->getDoctrine()->getManager();
            $villes = $em->getRepository(Ville::class)->findVilleLike($var,$nbResult);
        }

        return $this->render('front/recherche.html.twig', array(
            'villes' => $villes,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * activation d'une ville
     * @Route("/ville/add/{id}", name="front_ville_add")
     */
    public function villeAddAction($id,Request $request)
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion de la ville
        $em = $this->getDoctrine()->getManager();

        $ville = $em->getRepository(Ville::class)->find($id);
        
        $ville->setActif(1);
        $em->persist($ville);
        $em->flush();
        
        $salarie->setIdVille($ville);
        $salarie->setUpdated(new \DateTime());
        
        $editForm = $this->createForm(SalarieFrontType::class, $salarie);
        

        return $this->render('front/salarieEdit.html.twig', array(
            'salarie' => $salarie,
            'edit_form' => $editForm->createView(),
        ));
        
    }
    
    /**
     * visualisation d'une déclaration sur l'honneur
     * @Route("/declaration/print/{id}", name="front_declaration_print")
     */
    public function declarationPrintAction($id)
    {
        //récupératuion de la ville
        $em = $this->getDoctrine()->getManager();
        $declaration = $em->getRepository(DeclarationHonneur::class)->find($id);

        return $this->render('front/declarationshow.html.twig', array(
            'declaration' => $declaration
        ));
    }
    
    /**
     * Deletes a parcoursDate entity.
     * @Route("/mensuel/jour/delete/{id}", name="front_del_dep_jour")
     *     
     */
    public function deleteParcoursDateAction(Request $request, ParcoursDate $parcoursDate)
    {
        $idParcours = $parcoursDate->getIdParcours()->getId();
        //var_dump($idParcours);
        $form = $this->createParcoursDateDeleteForm($parcoursDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parcoursDate);
            $em->flush();
        }

        return $this->redirectToRoute('front_mensuel_detail',array('id' => $idParcours));
    }

    /**
     * Creates a form to delete a parcoursDate entity.
     *
     * @param ParcoursDate $parcoursDate The parcoursDate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createParcoursDateDeleteForm(ParcoursDate $parcoursDate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('front_del_dep_jour', array('id' => $parcoursDate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /********************************************************/
    /*                  STATISTIQUES                        */
    /********************************************************/
    /*
     * statistique sur les parcours annuels
     * réalisés en tenant compte de la période
     * définie en parametre
     */
    public function parcoursStatAnAction()
    {
        //recupération du user loggé
        $user = $this->getUser();
        
        //récupération du salarié
        $salarie = $user->getSalarie();
        
        $declaration = $this->verifUser($user);
        //var_dump($verif);
        if(is_null($declaration)){
             //$this->redirectToRoute('isen_front_profil');
            //var_dump($declaration);
            return $this->render('front/profil.html.twig',array(
            'salarie'       =>$salarie,
            'declaration'   => $declaration,
                ));
        }
        
        //récupératuion des stats
        $em = $this->getDoctrine()->getManager();

        //$parcours = $em->getRepository('IsenBackOfficeBundle:Parcours')->findByIdSalarie($user->getId());
        $parcours = $em->getRepository(Parcours::class)->findBy(array('idSalarie' => $user->getId()),
                array('annee' => 'DESC', 'idMois' => 'ASC')
                );

        
        
        return $this->render('front/parcoursList.html.twig',array(
            'parcours' => $parcours,
            'user'=>$user,
                ));
    }
}
