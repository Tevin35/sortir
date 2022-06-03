<?php

namespace App\Controller;

use App\Form\CreateTripType;
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
        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);
        dump($trip);

        //création d'un formulaire (On utilise le même formulaire: CreateTripType)
        $tripForm = $this -> CreateForm(CreateTripType::class, $trip);
        $tripForm -> handleRequest($request);

        if ($tripForm->isSubmitted()){

            $tripRepository->add($trip, true);
            $this->addFlash("success", "Sortie Modifiée");
            return $this->redirectToRoute("filter");

        }

        return $this->render('update_trip/updateTrip.twig', [
            'createTrip' => $tripForm->createView()
        ]);
    }

}
