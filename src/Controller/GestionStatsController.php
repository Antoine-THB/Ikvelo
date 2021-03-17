<?php

namespace App\Controller;

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
        return $this->render('gestion_stats/index.html.twig', [
            'controller_name' => 'GestionStatsController',
        ]);
    }
}
