<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function createPlace(PlaceRepository $placeRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $place = new Place();

        /**
         * pour avoir l'autocomplétion de tous les attributs de Place
         * @var Place $user
         */
        $user = $this->getUser();

        $placeForm = $this -> CreateForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()){

            $placeRepository->add($place, true);
            $this->addFlash('success', 'Lieu ajouté');
            return $this->redirectToRoute('app_create_trip');

        }

        return $this->render('place/createPlace.twig', [
            'createPlace' => $placeForm->createView()
        ]);
    }
}
