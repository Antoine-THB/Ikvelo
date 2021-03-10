<?php

namespace App\Controller;

use App\Entity\TypeConfig;
use App\Form\TypeConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * TypeConfig controller.
 *
 * @Route("typeconfig")
 */
class TypeConfigController extends AbstractController
{
    /**
     * Lists all TypeConfig entities.
     *
     * @Route("/", name="typeconfig_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeConfigs = $em->getRepository(TypeConfig::class)->findAll();

        return $this->render('typeconfig/index.html.twig', array(
            'typeConfigs' => $typeConfigs,
        ));
    }

    /**
     * Creates a new TypeConfig entity.
     *
     * @Route("/new", name="typeconfig_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeConfig = new TypeConfig();
        $form = $this->createForm(TypeConfigType::class, $typeConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeConfig);
            $em->flush();

            return $this->redirectToRoute('typeconfig_show', array('id' => $typeConfig->getId()));
        }

        return $this->render('typeconfig/new.html.twig', array(
            'typeConfig' => $typeConfig,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typeConfig entity.
     *
     * @Route("/{id}", name="typeconfig_show", methods={"GET"})
     */
    public function showAction(TypeConfig $typeConfig)
    {
        $deleteForm = $this->createDeleteForm($typeConfig);

        return $this->render('typeconfig/show.html.twig', array(
            'typeConfig' => $typeConfig,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typeConfig entity.
     *
     * @Route("/{id}/edit", name="typeconfig_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, TypeConfig $typeConfig)
    {
        $deleteForm = $this->createDeleteForm($typeConfig);
        $editForm = $this->createForm(TypeConfigType::class, $typeConfig);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typeconfig_edit', array('id' => $typeConfig->getId()));
        }

        return $this->render('typeconfig/edit.html.twig', array(
            'typeConfig' => $typeConfig,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typeConfig entity.
     *
     * @Route("/{id}", name="typeconfig_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, TypeConfig $typeConfig)
    {
        $form = $this->createDeleteForm($typeConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeConfig);
            $em->flush();
        }

        return $this->redirectToRoute('typeconfig_index');
    }

    /**
     * Creates a form to delete a typeConfig entity.
     *
     * @param TypeConfig $typeConfig The typeConfig entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeConfig $typeConfig)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeconfig_delete', array('id' => $typeConfig->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}