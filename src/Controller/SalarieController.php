<?php

namespace App\Controller;

use App\Entity\Salarie;
use App\Entity\DeclarationHonneur;
use App\Entity\Config;
use App\Libs\StatClass;
use App\Form\SalarieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Salarie controller.
 *
 * @Route("salarie")
 */
class SalarieController extends AbstractController
{
    /**
     * Lists all salarie entities.
     *
     * @Route("/", name="salarie_index", methods={"GET"})
     */
    public function indexAction()
    {
        //recuperation de l'année en cours
        $madate = new \DateTime();
        $an = $madate->format('Y');
        
        $em = $this->getDoctrine()->getManager();

        $salaries = $em->getRepository(Salarie::class)->findAll();
        //$salaries = $em->getRepository('IsenBackOfficeBundle:Salarie')->findInfoSalarieNbKmIndem($an);
        //var_dump($salaries);

        return $this->render('salarie/index.html.twig', array(
            'salaries'  => $salaries,
            'an'        => $an,
        ));
    }

    /**
     * Creates a new salarie entity.
     *
     * @Route("/new", name="salarie_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $salarie = new Salarie();
        $form = $this->createForm(SalarieType::class, $salarie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salarie->setCreated(new \DateTime());
            $salarie->setUpdated(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($salarie);
            $em->flush();

            return $this->redirectToRoute('salarie_show', array('id' => $salarie->getId()));
        }

        return $this->render('salarie/new.html.twig', array(
            'salarie' => $salarie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a salarie entity.
     *
     * @Route("/{id}", name="salarie_show", methods={"GET"})
     */
    public function showAction(Salarie $salarie)
    {
        $deleteForm = $this->createDeleteForm($salarie);
        
        //recupération de la déclaration sur l'honneur associée (active)
        //$declaration = new DeclarationHonneur();
        $em = $this->getDoctrine()->getManager();

        //$declaration = $em->getRepository('IsenBackOfficeBundle:DeclarationHonneur')->find4SalarieActif($salarie->getId());
        $declaration = $em->getRepository(DeclarationHonneur::class)
                ->findOneBy([
                    'idSalarie' => $salarie,
                    'actif'     => true
                ]);
        
        //recuperation des statistiques pour toutes les années
        //avec prise en compte du mois de début et de fin (en table parametre)
        
        
        //recuperation  du mois de référence
        $moisRef = $em->getRepository(Config::class)->findOneByLibelle("mois_fin_periode");
        $moisRefInt = intval($moisRef->getValueNum());
        
        $objStat = new StatClass($em);
        //$tabStat = $objStat->genereStatParcoursParAnSal($salarie);
        $tabStat = $objStat->genereStatParcoursParAnSalMoisInit($salarie,$moisRefInt);
        //var_dump($tabStat);

        return $this->render('salarie/show.html.twig', array(
            'salarie'       => $salarie,
            'declaration'   => $declaration,
            'delete_form'   => $deleteForm->createView(),
            'tabStatAn'     => $tabStat,
            'moisRef'       => $moisRef->getValue(),
        ));
    }

    /**
     * Displays a form to edit an existing salarie entity.
     *
     * @Route("/{id}/edit", name="salarie_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Salarie $salarie)
    {
        $deleteForm = $this->createDeleteForm($salarie);
        $editForm = $this->createForm(SalarieType::class, $salarie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $salarie->setUpdated(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('salarie_edit', array('id' => $salarie->getId()));
        }

        return $this->render('salarie/edit.html.twig', array(
            'salarie' => $salarie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a salarie entity.
     *
     * @Route("/{id}", name="salarie_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Salarie $salarie)
    {
        $form = $this->createDeleteForm($salarie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salarie);
            $em->flush();
        }

        return $this->redirectToRoute('salarie_index');
    }

    /**
     * Creates a form to delete a salarie entity.
     *
     * @param Salarie $salarie The salarie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Salarie $salarie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salarie_delete', array('id' => $salarie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}