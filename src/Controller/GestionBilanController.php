<?php

namespace App\Controller;


use App\Entity\Parcours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BilanGestionType;

use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * Gestion Bilan controller.
 *
 * @Route("gestion/bilan")
 */
class GestionBilanController extends AbstractController
{
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
        //$parcours = $em->getRepository('IsenBackOfficeBundle:Parcours')->findParcoursDateMois($varAnnee,$varIdMois,$nbResult);
        $parcours = $em->getRepository(Parcours::class)->findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie);
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
        //Récupération de mes données avec les paramètres su formulaire
        $nbResult = null;
        $em = $this->getDoctrine()->getManager();
        if ($varAnnee == "null") {
            $varAnnee = null;
        }
        if ($varIdMois == "null") {
            $varIdMois = null;
        }
        if ($varIdService == "null") {
            $varIdService = null;
        }
        if ($varIdEntreprise == "null") {
            $varIdEntreprise = null;
        }
        if ($varIdSalarie == "null") {
            $varIdSalarie = null;
        }
        
        $parcours = $em->getRepository(Parcours::class)->findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie);
        //création d'une réponse
        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($parcours, $varAnnee, $varIdEntreprise, $varIdMois, $varIdSalarie, $varIdService) {
                $handle = fopen('php://output', 'r+');
                foreach ($parcours as $parcour) {
                    //array list fields you need to export
                    $data = array(
                        $parcour->getAnnee(),
                        $parcour->getIdMois(),
                        $parcour->getIdSalarie(),
                        $parcour->getIdSalarie()->getIdService(),
                        $parcour->getIdSalarie()->getIdEntreprise(),
                        $parcour->getNbKmEffectue(),
                        $parcour->getIndemnisation(),
                    );
                    fputcsv($handle, $data);
                }
                fclose($handle);
            }
        );
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
    


}
