<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Salarie;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SalarieType;
use App\Libs\StatClass;
use App\Entity\Config;
use App\Form\SalarieGestionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gestion Salariés controller.
 *
 * @Route("gestion/salaries")
 */
class SalarieGestionController extends AbstractController
{
    /**
     * @Route("/", name="salarie_gestion_index", methods={"GET", "POST"})
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $salaries = $em->getRepository(Salarie::class)->findAll();

        return $this->render('salarie_gestion/index.html.twig', array(
            'salaries'  => $salaries,

        ));
    }
        /**
     * Creates a new salarie entity.
     *
     * @Route("/new", name="salarie_new_gestion", methods={"GET", "POST"})
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

        return $this->render('salarie_gestion/new.html.twig', array(
            'salarie' => $salarie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Permet de mettre un salarie actif ou de le désqctiver
     *
     * @Route("/{id}", name="salarie_click_gestion")
     */
    public function actifUser(Salarie $salarie)
    {
        if($salarie->getActif()){
            $salarie->setActif(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($salarie);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Actif"], 200);
        }
        else{
            $salarie->setActif(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($salarie);
            $em->flush();
            return $this->json(['code'=>200, 'message'=>"Inactif"], 200);
        }
        return $this->json(['code'=>200, 'message'=>"Salut"], 200);

    }



    /**
     * Finds and displays a salarie entity.
     *
     * @Route("/show/{id}", name="salarie_show_gestion", methods={"GET"})
     */
    public function showAction(Salarie $salarie)
    {
        $deleteForm = $this->createDeleteForm($salarie);
        
        //recupération de la déclaration sur l'honneur associée (active)
        //$declaration = new DeclarationHonneur();
        $em = $this->getDoctrine()->getManager();

        
        //recuperation des statistiques pour toutes les années
        //avec prise en compte du mois de début et de fin (en table parametre)
        
        
        //recuperation  du mois de référence
        $moisRef = $em->getRepository(Config::class)->findOneByLibelle("mois_fin_periode");
        $moisRefInt = intval($moisRef->getValueNum());
        
        $objStat = new StatClass($em);
        //$tabStat = $objStat->genereStatParcoursParAnSal($salarie);
        $tabStat = $objStat->genereStatParcoursParAnSalMoisInit($salarie,$moisRefInt);
        //var_dump($tabStat);

        return $this->render('salarie_gestion/show.html.twig', array(
            'salarie'       => $salarie,
            'tabStatAn'     => $tabStat,
            'moisRef'       => $moisRef->getValue(),
        ));
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
