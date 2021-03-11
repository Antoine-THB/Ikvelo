<?php

namespace App\Controller;

use App\Entity\DeclarationHonneur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Config controller.
 *
 * @Route("admin/declaration")
 */
class DeclarationHonneurController extends AbstractController
{
    /**
     * Lists all config entities.
     *
     * @Route("/", name="declaration_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $declarations = $em->getRepository(DeclarationHonneur::class)->findAll();

        return $this->render('declarationhonneur\index.html.twig', array(
            'declarations' => $declarations,
        ));
    }

    /**
     * Creates a new config entity.
     *
     * @Route("/new", name="declaration_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $declaration = new DeclarationHonneur();
        $form = $this->createForm(DeclarationHonneur::class, $declaration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $declaration->setCreated(new \DateTime());
            $declaration->setUpdated(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($declaration);
            $em->flush();

            return $this->redirectToRoute('declaration_show', array('id' => $declaration->getId()));
        }

        return $this->render('declarationhonneur\new.html.twig', array(
            'declaration' => $declaration,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a config entity.
     *
     * @Route("/{id}", name="declaration_show", methods={"GET"})
     */
    public function showAction(DeclarationHonneur $declaration)
    {
        $deleteForm = $this->createDeleteForm($declaration);

        return $this->render('declarationhonneur\show.html.twig', array(
            'declaration' => $declaration,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing config entity.
     *
     * @Route("/{id}/edit", name="declaration_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, DeclarationHonneur $declaration)
    {
        $deleteForm = $this->createDeleteForm($declaration);
        $editForm = $this->createForm(DeclarationHonneur::class, $declaration);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('declaration_edit', array('id' => $declaration->getId()));
        }

        return $this->render('declarationhonneur\edit.html.twig', array(
            'declaration' => $declaration,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a config entity.
     *
     * @Route("/{id}", name="declaration_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, DeclarationHonneur $declaration)
    {
        $form = $this->createDeleteForm($declaration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($declaration);
            $em->flush();
        }

        return $this->redirectToRoute('declaration_index');
    }

    /**
     * Creates a form to delete a config entity.
     *
     * @param DeclarationHonneur $declaration The config entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DeclarationHonneur $declaration)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('declaration_delete', array('id' => $declaration->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

