<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Admin controller.
 *
 * @Route("admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="isen_back_office_homepage")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
