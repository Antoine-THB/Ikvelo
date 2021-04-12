<?php

namespace App\Controller;

use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Role controller.
 *
 * @Route("admin/role")
 * @Security("is_granted('ROLE_SUP_ADMIN')")
 */
class RoleController extends AbstractController
{
    /**
     * Lists all role entities.
     *
     * @Route("/", name="role_index", methods={"GET"})
     * 
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $roles = $em->getRepository(Role::class)->findAll();

        return $this->render('role\index.html.twig', array(
            'roles' => $roles,
        ));
    }

    /**
     * Creates a new role entity.
     *
     * @Route("/new", name="role_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $role = new Role();
        $form = $this->createForm('.\Form\RoleType', $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            return $this->redirectToRoute('role_show', array('id' => $role->getId()));
        }

        return $this->render('role\new.html.twig', array(
            'role' => $role,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a role entity.
     *
     * @Route("/{id}", name="role_show", methods={"GET"})
     */
    public function showAction(Role $role)
    {
        $deleteForm = $this->createDeleteForm($role);

        return $this->render('role\show.html.twig', array(
            'role' => $role,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing role entity.
     *
     * @Route("/{id}/edit", name="role_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Role $role)
    {
        $deleteForm = $this->createDeleteForm($role);
        $editForm = $this->createForm('.\Form\RoleType', $role);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('role_edit', array('id' => $role->getId()));
        }

        return $this->render('role\edit.html.twig', array(
            'role' => $role,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a role entity.
     *
     * @Route("/{id}", name="role_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Role $role)
    {
        $form = $this->createDeleteForm($role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($role);
            $em->flush();
        }

        return $this->redirectToRoute('role_index');
    }

    /**
     * Creates a form to delete a role entity.
     *
     * @param Role $role The role entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Role $role)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('role_delete', array('id' => $role->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
