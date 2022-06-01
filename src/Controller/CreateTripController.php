<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTripController extends AbstractController
{
    #[Route('/create/trip', name: 'app_create_trip')]
    public function index(): Response
    {
        return $this->render('create_trip/index.html.twig', [
            'controller_name' => 'CreateTripController',
        ]);
    }
}
