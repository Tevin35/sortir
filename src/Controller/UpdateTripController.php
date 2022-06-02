<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateTripController extends AbstractController
{
    #[Route('/update/trip/{id}', name: 'app_update_trip')]
    public function updateTrip($id, TripRepository $tripRepository, Request $request): Response
    {




        return $this->render('update_trip.twig', [
            'controller_name' => 'UpdateTripController',
        ]);
    }
}
