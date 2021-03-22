<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parcours;
use App\Form\ValidationGestionType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gestion Validation controller.
 *
 * @Route("gestion/validation")
 */
class GestionValidationController extends AbstractController
{
    /**
     * @Route("/", name="gestion_validation_index")
     */
    public function index(Request $request)
    {
        $nbResult = null;
        
        $em = $this->getDoctrine()->getManager();
        

        //Création du formumlaire pour le choix des parcours
        $form = $this->createForm(ValidationGestionType::class);
        $form->handleRequest($request);

        //Récupération du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $varValidation = $form->get('validation')->getData();

    
        }else{
            $varValidation = null;
            
        }

        //Il me faudra surement changer la requête SQL 
        $parcours = $em->getRepository(Parcours::class)->findWithNbAndValidation($nbResult, $varValidation);

        return $this->render('gestion_validation/index.html.twig', [
            'parcours' => $parcours,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * Fonction qui permet de valider un trajet
     * 
     * @Route("/validation/{id}", name="gestion_validation_Trajet")
     */
    public function validation(Parcours $parcours)
    {
        if($parcours->getValidation()){
            $parcours->setValidation(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($parcours);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Actif"], 200);
        }
        else{
            $parcours->setValidation(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($parcours);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Inactif"], 200);
        }

    }
}
