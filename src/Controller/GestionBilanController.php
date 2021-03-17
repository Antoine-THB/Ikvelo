<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BilanGestionType;
use App\Entity\Parcours;

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
    
        }else{
            $madate = new \DateTime();
            $varAnnee = $madate->format('Y');
            $varIdMois = null;
            
        }
        
        $em = $this->getDoctrine()->getManager();
        //$parcours = $em->getRepository('IsenBackOfficeBundle:Parcours')->findParcoursDateMois($varAnnee,$varIdMois,$nbResult);
        $parcours = $em->getRepository(Parcours::class)->findParcoursDateMoisGlob($varAnnee,$varIdMois,$nbResult);
        //var_dump($parcours);
        
        return $this->render('gestion_bilan/index.html.twig', array(
            'parcours'     => $parcours,
            'form'         => $form->createView(),
            'varAnnee'     => $varAnnee,
            'varIdMois'    => $varIdMois,
        ));
    }


}
