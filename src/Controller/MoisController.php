<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mois;
use App\Form\MoisType;


/**
 * Mois controller.
 *
 * @Route("admin/mois")
 */
class MoisController extends AbstractController
{
    /**
     * Lists all mois entities.
     *
     * @Route("", name="mois_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mois = $em->getRepository(Mois::class)->findAll();

        return $this->render('mois\index.html.twig', array(
            'mois' => $mois,
        ));
    }

    /**
     * Creates a new mois entity.
     *
     * @Route("/new", name="mois_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $mois = new Mois();
        $form = $this->createForm(MoisType::class, $mois);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mois);
            $em->flush();

            return $this->redirectToRoute('mois_show', array('id' => $mois->getId()));
        }

        return $this->render('mois\new.html.twig', array(
            'mois' => $mois,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a mois entity.
     *
     * @Route("/{id}", name="mois_show", methods={"GET"})
     */
    public function showAction(Mois $mois)
    {
        $deleteForm = $this->createDeleteForm($mois);

        return $this->render('mois\show.html.twig', array(
            'mois' => $mois,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mois entity.
     *
     * @Route("/{id}/edit", name="mois_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Mois $mois)
    {
        $deleteForm = $this->createDeleteForm($mois);
        $editForm = $this->createForm(Mois::class, $mois);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mois_edit', array('id' => $mois->getId()));
        }

        return $this->render('mois\edit.html.twig', array(
            'mois' => $mois,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a mois entity.
     *
     * @Route("/{id}", name="mois_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Mois $mois)
    {
        $form = $this->createDeleteForm($mois);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mois);
            $em->flush();
        }

        return $this->redirectToRoute('mois_index');
    }

    /**
     * Creates a form to delete a mois entity.
     *
     * @param Mois $mois The mois entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mois $mois)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mois_delete', array('id' => $mois->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
