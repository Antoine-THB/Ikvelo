<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Mois;
use App\Entity\Parcours;
use App\Entity\Salarie;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BilanGestionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;




use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Gestion Bilan controller.
 *
 * @Route("gestion/bilan")
 */
class GestionBilanController extends AbstractController
{
    // private $rootDir;

    // public function __construct(KernelInterface $racineKernel){
    //     $this->rootDir = $racineKernel->getProjectDir();
    // }
    /**
     * @Route("/", name="gestion_bilan_index")
     */
    public function index(Request $request)
    {
        //nombre d'éléments récupérés
        $nbResult = null;

        $parcours = NULL;
        $form = $this->createForm(BilanGestionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $varAnnee = $form->get('annee')->getData();
            $varIdMois = $form->get('idMois')->getData();
            $varIdService = $form->get('idService')->getData();
            $varIdEntreprise = $form->get('idEntreprise')->getData();
            $varIdSalarie = $form->get('idSalarie')->getData();
    
        }else{
            $madate = new \DateTime();
            $varAnnee = null;
            $varIdMois = null;
            $varIdService = null;
            $varIdEntreprise = null;
            $varIdSalarie = null;
            
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $parcours = $em->getRepository(Parcours::class)->findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie);
        //Recupération des id des variables pour la requete sql du fichier csv
        if (!($varIdMois == null)) {
            $varIdMois = $varIdMois->getID();
        }
        if (!($varIdService == null)) {
            $varIdService = $varIdService->getID();
        }
        if (!($varIdEntreprise == null)) {
            $varIdEntreprise = $varIdEntreprise->getID();
        }
        if (!($varIdSalarie == null)) {
            $varIdSalarie = $varIdSalarie->getID();
        }
        
        //var_dump($parcours);
        // $parcours = $em->getRepository(Parcours::class)->findParcoursDateMoisGlob($varAnnee,$varIdMois,$nbResult);
        return $this->render('gestion_bilan/index.html.twig', array(
            'parcours'     => $parcours,
            'form'         => $form->createView(),
            'varAnnee'     => $varAnnee,
            'varIdMois'    => $varIdMois,
            'varIdService' => $varIdService,
            'varIdEntreprise'=>$varIdEntreprise,
            'varIdSalarie'=>$varIdSalarie,
        ));
    }

    /**
     * Recherche de parcours par formulaires.
     *
     * @Route("/csv/{varAnnee}/{varIdMois}/{varIdService}/{varIdEntreprise}/{varIdSalarie}", name="bilan_csv")
     */
    public function BilanParcours($varAnnee = null, $varIdMois = null, $varIdService = null, $varIdEntreprise = null, $varIdSalarie = null)
    {
        /*
        * Fonction qui prend en entré les différents filtres de l'utilisateur et qui ressort une répoonse permettant le téléchargement d'un fichier csv
        */
        //Récupération de mes données avec les paramètres su formulaire
        $nbResult = null;
        $filename = "Bilan";
        $em = $this->getDoctrine()->getManager();
        if ($varAnnee == "null") {
            $varAnnee = null;
        }
        else {
            $filename .= "_".$varAnnee;
        }
        if ($varIdMois == "null") {
            $varIdMois = null;
        }
        else {
            $filename .= "_".$em->getRepository(Mois::class)->find($varIdMois)->getMois();
        }
        if ($varIdService == "null") {
            $varIdService = null;
        }
        else {
            $filename .= "_".$em->getRepository(Service::class)->find($varIdService)->getService();
        }
        if ($varIdEntreprise == "null") {
            $varIdEntreprise = null;
        }
        else {
            $filename .= "_".$em->getRepository(Entreprise::class)->find($varIdEntreprise)->getEntreprise();
        }
        if ($varIdSalarie == "null") {
            $varIdSalarie = null;
        }
        else {
            $filename .= "_".$em->getRepository(Salarie::class)->find($varIdSalarie)->getSalarie();
        }
        $madate = new \DateTime();

        $filename.="-".$madate->format('d-m-Y');
        $filename.=".csv";
        
        $parcours = $em->getRepository(Parcours::class)->findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie);
        //création d'une réponse


        //On intègre les données dans une variable pour ensuite les envoyés à la réponse 
        $data = "Année;Mois;Salarié;Service;Entreprise;Nb Km;Indemnisation;\n";
        foreach($parcours as $parcour){
            $data .= 
                $parcour->getAnnee().";".
                $parcour->getIdMois().";".
                $parcour->getIdSalarie().";".
                $parcour->getIdSalarie()->getIdService().";".
                $parcour->getIdSalarie()->getIdEntreprise().";".
                $parcour->getNbKmEffectue().";".
                $parcour->getIndemnisation().";\n"
            ;
        }
        //On retourne une réponse de manière a obtenir un fichier excel à la fin
        return new Response(
            $data,
            200,
            [
                'Content-Type' => 'application/vnd.ms-excel; charset=utf8',
                "Content-disposition" => "attachment; filename=".$filename
            ]
            );
    }
    


}
