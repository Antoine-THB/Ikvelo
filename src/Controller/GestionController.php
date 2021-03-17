<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion controller.
 *
 * @Route("gestion")
 */
class GestionController extends AbstractController
{
    /**
     * @Route("/", name="gestion")
     */
    public function index()
    {
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
}
