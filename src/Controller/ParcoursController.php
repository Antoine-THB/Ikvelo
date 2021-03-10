<?php

namespace App\Controller;

use App\Entity\Parcours;
use App\Entity\ParcoursDate;
use App\Form\ParcoursSearchType;
use App\Form\ParcoursType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Parcour controller.
 *
 * @Route("parcours")
 */
class ParcoursController extends AbstractController
{
    /**
     * Lists all parcour entities.
     *
     * @Route("/", name="parcours_index", methods={"GET"})
     */
    public function indexAction()
    {
        //nombre d'éléments récupérés
        $nbResult = null;
        
        $em = $this->getDoctrine()->getManager();

        //$parcours = $em->getRepository(Parcours::class)->findAll();
        //$parcours = $em->getRepository(Parcours::class)->findGlobal($nbResult);
        $parcours = $em->getRepository(Parcours::class)->findWithNb($nbResult);
        
        return $this->render('parcours/index.html.twig', array(
            'parcours' => $parcours,
        ));
    }
    
    /**
     * Recherche de parcours par formulaires.
     *
     * @Route("/recherche", name="parcours_recherche", methods={"GET", "POST"})
     */
    public function rechercheParcoursAction(Request $request)
    {
        //nombre d'éléments récupérés
        $nbResult = null;
        
        $parcours = NULL;
        $form = $this->createForm(ParcoursSearchType::class);
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
        
        return $this->render('parcours/recherche.html.twig', array(
            'parcours'     => $parcours,
            'form'         => $form->createView(),
            'varAnnee'     => $varAnnee,
            'varIdMois'    => $varIdMois,
        ));
    }
    
    /**
     * Recherche de parcours par formulaires.
     *
     * @Route("/bilan/impression/{varAnnee}/{varIdMois}", name="bilan_impression", methods={"GET", "POST"})
     */
    public function bilanImpressionParcoursAction($varAnnee,$varIdMois=null)
    {
        //nombre d'éléments récupérés
        $nbResult = null;
        
        $em = $this->getDoctrine()->getManager();
        $parcours = $em->getRepository(Parcours::class)->findParcoursDateMois($varAnnee,$varIdMois,$nbResult);

        return $this->render('parcours/bilanImpression.html.twig', array(
            'parcours'     => $parcours,
            'varAnnee'     => $varAnnee,
            'varIdMois'    => $varIdMois,
        ));
    }

    /**
     * Creates a new parcour entity.
     *
     * @Route("/new", name="parcours_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parcour = new Parcours();
        $parcour->setDateCreation(new \DateTime());
        $parcour->setCreated(new \DateTime());
        $parcour->setUpdated(new \DateTime());
        
        $form = $this->createForm(ParcoursType::class, $parcour);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parcour);
            $em->flush();

            return $this->redirectToRoute('parcours_show', array('id' => $parcour->getId()));
        }

        return $this->render('parcours/new.html.twig', array(
            'parcour' => $parcour,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parcour entity.
     *
     * @Route("/{id}", name="parcours_show", methods={"GET"})
     */
    public function showAction(Parcours $parcour)
    {
        $deleteForm = $this->createDeleteForm($parcour);
        
        //recuperation des deplacements jours associés
        $em = $this->getDoctrine()->getManager();
        $parcourJours = $em->getRepository(ParcoursDate::class)
                ->findBy(
                    ['idParcours'    => $parcour->getId()],
                    ['jourNum'        =>'ASC']
                        );
                //findByIdParcours($parcour->getId());
        
        //recuperation du nombre de km et indemnistes calculée 
        $result = $em->getRepository(ParcoursDate::class)
                ->recupParcoursDate4Parcours( $parcour->getId());
       
        
        $totIndemnisation = $result[0]['totIndemnisation'];
        $nbKmEffectue =  $result[0]['nbKmEffectue'];
         //var_dump($nbKmEffectue);
        
        return $this->render('parcours/show.html.twig', array(
            'parcour'       => $parcour,
            'delete_form'   => $deleteForm->createView(),
            'parcourJours'  => $parcourJours,
            'totIndemnisation'  => $totIndemnisation,
            'nbKmEffectue'  => $nbKmEffectue,
        ));
    }

    /**
     * Displays a form to edit an existing parcour entity.
     *
     * @Route("/{id}/edit", name="parcours_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Parcours $parcour)
    {
        $deleteForm = $this->createDeleteForm($parcour);
        $editForm = $this->createForm(ParcoursType::class, $parcour);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parcours_edit', array('id' => $parcour->getId()));
        }

        return $this->render('parcours/edit.html.twig', array(
            'parcour' => $parcour,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parcour entity.
     *
     * @Route("/{id}", name="parcours_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Parcours $parcour)
    {
        $form = $this->createDeleteForm($parcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parcour);
            $em->flush();
        }

        return $this->redirectToRoute('parcours_index');
    }

    /**
     * Creates a form to delete a parcour entity.
     *
     * @param Parcours $parcour The parcour entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Parcours $parcour)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parcours_delete', array('id' => $parcour->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    /**
     * Validation du parcours 
     *
     * @Route("/{id}/valid", name="parcours_valid", methods={"GET", "POST"})
     */
    public function validAction(Request $request, Parcours $parcour)
    {
        $em = $this->getDoctrine()->getManager();
        
        //modification du Parcours pour cloture
        $parcour->setValidation(1);
        
        $em->persist($parcour);
            
        $em->flush();
        
        $parcourJours = $em->getRepository(ParcoursDate::class)->findByIdParcours($parcour->getId());

        return $this->render('parcours/show.html.twig', array(
            'parcour'       => $parcour,
            'parcourJours'  => $parcourJours,
        ));

    }
    
    
}
