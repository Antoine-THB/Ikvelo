<?php

namespace App\Controller;

use App\Entity\Parcours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Gestion Stats controller.
 *
 * @Route("gestion/stats")
 */
class GestionStatsController extends AbstractController
{
    /**
     * @Route("/", name="gestion_stats_index")
     */
    public function index()
    {
        $annee=date('Y');
        $global = $this->getDoctrine()->getManager()->getRepository(Parcours::class)->getStatToutAnMoisInit();
        $annee = $this->getDoctrine()->getManager()->getRepository(Parcours::class)->getStatAn($annee);
        return $this->render('gestion_stats/index.html.twig', [
            'Global' => $global,
            'Total_annee'=>$annee,
            'controller_name' => 'GestionStatsController',
        ]);
    }
}
