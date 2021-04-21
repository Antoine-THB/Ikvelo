<?php

namespace App\Controller;

use App\Entity\Abonnement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parcours;
use App\Entity\TicketJournalier;
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
        $abonnement = $em->getRepository(Abonnement::class)->findWithNbAndValidation($nbResult, $varValidation);
        $tickets = $em->getRepository(TicketJournalier::class)->findWithNbAndValidation($nbResult, $varValidation);

        return $this->render('gestion_validation/index.html.twig', [
            'parcours' => $parcours,
            'abonnement' => $abonnement,
            'tickets' => $tickets,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * Fonction qui permet de valider un trajet
     * 
     * @Route("/validation/trajet/{id}", name="gestion_validation_Trajet")
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
     /**
     * Fonction qui permet de valider un trajet
     * 
     * @Route("/validation/abonnement/{id}", name="gestion_validation_Abonnement")
     */
    public function validationAbonnement(Abonnement $abo)
    {
        if($abo->getValidation()){
            $abo->setValidation(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($abo);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Actif"], 200);
        }
        else{
            $abo->setValidation(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($abo);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Inactif"], 200);
        }

    }
         /**
     * Fonction qui permet de valider un trajet
     * 
     * @Route("/validation/ticket/{id}", name="gestion_validation_ticket")
     */
    public function validationTicket(TicketJournalier $ticket)
    {
        if($ticket->getValidation()){
            $ticket->setValidation(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Actif"], 200);
        }
        else{
            $ticket->setValidation(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Inactif"], 200);
        }

    }
    /**
     * Finds and displays a ticket entity.
     *
     * @Route("/ticket/show/{id}", name="ticket_show_gestion", methods={"GET"})
     */
    public function showTicketAction(TicketJournalier $ticket)
    {
        return $this->render('gestion_validation/showTicket.html.twig', array(
            'ticket'       => $ticket,
        ));
    }

    /**
     * Finds and displays a abonnement entity.
     *
     * @Route("/abonnement/show/{id}", name="abonnement_show_gestion", methods={"GET"})
     */
    public function showAction(Abonnement $abo)
    {
        return $this->render('gestion_validation/show.html.twig', array(
            'abonnement'       => $abo,
        ));
    }
}
