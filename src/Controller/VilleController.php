<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\RechercheVilleType;
use App\Form\VilleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Ville controller.
 *
 * @Route("admin/ville")
 */
class VilleController extends AbstractController
{
    /**
    * @Route("/search-ville", name="search_ville", defaults={"_format"="json"}, methods={"GET"})

    */
    public function searchVilleByAction(Request $request)
    {
        //nombre d'éléments récupérés
        $nbResult = 10;
        
        $em = $this->getDoctrine()->getManager();

        $q = $request->query->get('term'); // use "term" instead of "q" for jquery-ui

        $results = $em->getRepository(Ville::class)->findVilleLike($q,$nbResult);

        return $this->render("ville/search.json.twig", ['villes' => $results]);
    }
    
    /**
     * Recherche de villes par formulaires.
     *
     * @Route("/recherche", name="ville_recherche", methods={"GET", "POST"})
     */
    public function rechercheVilleAction(Request $request)
    {
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

        return $this->render('ville/recherche.html.twig', array(
            'villes' => $villes,
            'form' => $form->createView(),
        ));
    }
    
    
    /**
     * Lists all ville entities.
     *
     * @Route("/", name="ville_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $villes = $em->getRepository(Ville::class)->findByActif('TRUE');

        return $this->render('ville/index.html.twig', array(
            'villes' => $villes,
        ));
    }

    /**
     * Creates a new ville entity.
     *
     * @Route("/new", name="ville_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();

            return $this->redirectToRoute('ville_show', array('id' => $ville->getId()));
        }

        return $this->render('ville/new.html.twig', array(
            'ville' => $ville,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ville entity.
     *
     * @Route("/{id}", name="ville_show", methods={"GET"})
     */
    public function showAction(Ville $ville)
    {
        $deleteForm = $this->createDeleteForm($ville);

        return $this->render('ville/show.html.twig', array(
            'ville' => $ville,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ville entity.
     *
     * @Route("/{id}/edit", name="ville_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Ville $ville)
    {
        $deleteForm = $this->createDeleteForm($ville);
        $editForm = $this->createForm(VilleType::class, $ville);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ville_edit', array('id' => $ville->getId()));
        }

        return $this->render('ville/edit.html.twig', array(
            'ville' => $ville,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ville entity.
     *
     * @Route("/{id}", name="ville_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Ville $ville)
    {
        $form = $this->createDeleteForm($ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ville);
            $em->flush();
        }

        return $this->redirectToRoute('ville_index');
    }

    /**
     * Creates a form to delete a ville entity.
     *
     * @param Ville $ville The ville entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ville $ville)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ville_delete', array('id' => $ville->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
}
