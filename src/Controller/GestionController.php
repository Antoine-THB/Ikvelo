<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion controller.
 *
 * @Route("admin")
 */
class GestionController extends AbstractController
{
    /**
     * @Route("/gestion", name="gestion")
     */
    public function index()
    {
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
}
