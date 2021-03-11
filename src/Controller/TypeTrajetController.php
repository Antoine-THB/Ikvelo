<?php

namespace App\Controller;

use App\Entity\TypeTrajet;
use App\Form\TypeTrajetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Typetrajet controller.
 *
 * @Route("admin/typetrajet")
 */
class TypeTrajetController extends AbstractController
{
    /**
     * Lists all typeTrajet entities.
     *
     * @Route("/", name="typetrajet_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeTrajets = $em->getRepository(TypeTrajet::class)->findAll();

        return $this->render('typetrajet/index.html.twig', array(
            'typeTrajets' => $typeTrajets,
        ));
    }

    /**
     * Creates a new typeTrajet entity.
     *
     * @Route("/new", name="typetrajet_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeTrajet = new Typetrajet();
        $form = $this->createForm(TypeTrajetType::class, $typeTrajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeTrajet);
            $em->flush();

            return $this->redirectToRoute('typetrajet_show', array('id' => $typeTrajet->getId()));
        }

        return $this->render('typetrajet/new.html.twig', array(
            'typeTrajet' => $typeTrajet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeTrajet entity.
     *
     * @Route("/{id}", name="typetrajet_show", methods={"GET"})
     */
    public function showAction(TypeTrajet $typeTrajet)
    {
        $deleteForm = $this->createDeleteForm($typeTrajet);

        return $this->render('typetrajet/show.html.twig', array(
            'typeTrajet' => $typeTrajet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeTrajet entity.
     *
     * @Route("/{id}/edit", name="typetrajet_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, TypeTrajet $typeTrajet)
    {
        $deleteForm = $this->createDeleteForm($typeTrajet);
        $editForm = $this->createForm(TypeTrajetType::class, $typeTrajet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typetrajet_edit', array('id' => $typeTrajet->getId()));
        }

        return $this->render('typetrajet/edit.html.twig', array(
            'typeTrajet' => $typeTrajet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeTrajet entity.
     *
     * @Route("/{id}", name="typetrajet_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, TypeTrajet $typeTrajet)
    {
        $form = $this->createDeleteForm($typeTrajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeTrajet);
            $em->flush();
        }

        return $this->redirectToRoute('typetrajet_index');
    }

    /**
     * Creates a form to delete a typeTrajet entity.
     *
     * @param TypeTrajet $typeTrajet The typeTrajet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeTrajet $typeTrajet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typetrajet_delete', array('id' => $typeTrajet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}