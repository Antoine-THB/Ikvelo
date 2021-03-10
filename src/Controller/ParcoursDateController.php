<?php

namespace App\Controller;

use App\Entity\ParcoursDate;
use App\Form\ParcoursDateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Parcoursdate controller.
 *
 * @Route("parcoursdate")
 */
class ParcoursDateController extends AbstractController
{
    /**
     * Lists all parcoursDate entities.
     *
     * @Route("/", name="parcoursdate_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parcoursDates = $em->getRepository(ParcoursDate::class)->findAll();

        return $this->render('parcoursdate/index.html.twig', array(
            'parcoursDates' => $parcoursDates,
        ));
    }

    /**
     * Creates a new parcoursDate entity.
     *
     * @Route("/new", name="parcoursdate_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parcoursDate = new Parcoursdate();
        $form = $this->createForm(ParcoursDateType::class, $parcoursDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parcoursDate);
            $em->flush();

            return $this->redirectToRoute('parcoursdate_show', array('id' => $parcoursDate->getId()));
        }

        return $this->render('parcoursdate/new.html.twig', array(
            'parcoursDate' => $parcoursDate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parcoursDate entity.
     *
     * @Route("/{id}", name="parcoursdate_show", methods={"GET"})
     */
    public function showAction(ParcoursDate $parcoursDate)
    {
        $deleteForm = $this->createDeleteForm($parcoursDate);

        return $this->render('parcoursdate/show.html.twig', array(
            'parcoursDate' => $parcoursDate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parcoursDate entity.
     *
     * @Route("/{id}/edit", name="parcoursdate_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, ParcoursDate $parcoursDate)
    {
        $deleteForm = $this->createDeleteForm($parcoursDate);
        $editForm = $this->createForm(ParcoursDateType::class, $parcoursDate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parcoursdate_edit', array('id' => $parcoursDate->getId()));
        }

        return $this->render('parcoursdate/edit.html.twig', array(
            'parcoursDate' => $parcoursDate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parcoursDate entity.
     *
     * @Route("/{id}", name="parcoursdate_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, ParcoursDate $parcoursDate)
    {
        $form = $this->createDeleteForm($parcoursDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parcoursDate);
            $em->flush();
        }

        return $this->redirectToRoute('parcoursdate_index');
    }

    /**
     * Creates a form to delete a parcoursDate entity.
     *
     * @param ParcoursDate $parcoursDate The parcoursDate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ParcoursDate $parcoursDate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parcoursdate_delete', array('id' => $parcoursDate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

