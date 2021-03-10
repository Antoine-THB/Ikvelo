<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Entreprise controller.
 *
 * @Route("entreprise")
 */
class EntrepriseController extends AbstractController
{
    /**
     * Lists all entreprise entities.
     *
     * @Route("/", name="entreprise_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entreprises = $em->getRepository(Entreprise::class)->findAll();

        return $this->render('entreprise/index.html.twig', array(
            'entreprises' => $entreprises,
        ));
    }

    /**
     * Creates a new entreprise entity.
     *
     * @Route("/new", name="entreprise_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(Entreprise::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreprise->setCreated(new \DateTime());
            $entreprise->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entreprise);
            $em->flush();

            return $this->redirectToRoute('entreprise_show', array('id' => $entreprise->getId()));
        }

        return $this->render('entreprise/new.html.twig', array(
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a entreprise entity.
     *
     * @Route("/{id}", name="entreprise_show", methods={"GET"})
     */
    public function showAction(Entreprise $entreprise)
    {
        $deleteForm = $this->createDeleteForm($entreprise);

        return $this->render('entreprise/show.html.twig', array(
            'entreprise' => $entreprise,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing entreprise entity.
     *
     * @Route("/{id}/edit", name="entreprise_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Entreprise $entreprise)
    {
        $deleteForm = $this->createDeleteForm($entreprise);
        $editForm = $this->createForm(Entreprise::class, $entreprise);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entreprise_edit', array('id' => $entreprise->getId()));
        }

        return $this->render('entreprise/edit.html.twig', array(
            'entreprise' => $entreprise,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a entreprise entity.
     *
     * @Route("/{id}", name="entreprise_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Entreprise $entreprise)
    {
        $form = $this->createDeleteForm($entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entreprise);
            $em->flush();
        }

        return $this->redirectToRoute('entreprise_index');
    }

    /**
     * Creates a form to delete a entreprise entity.
     *
     * @param Entreprise $entreprise The entreprise entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Entreprise $entreprise)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entreprise_delete', array('id' => $entreprise->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

