<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\CreateTripType;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTripController extends AbstractController
{
    #[Route('/create/trip', name: 'app_create_trip')]
    public function addTrip(TripRepository $tripRepository, StateRepository $stateRepository, Request $request): Response
    {
        $trip = new Trip();
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $user
         */
        $user = $this->getUser();

        //création d'un formulaire
        $tripForm = $this -> CreateForm(CreateTripType::class, $trip);
        $tripForm -> handleRequest($request);
        dump('ici');

        //traitement du formulaire
        if($tripForm -> isSubmitted() && $tripForm->isValid()){


            //On conditionne la valeur de State en fonction du bouton qui a été cliqué
            if($tripForm->get('enregistrer')->isClicked()){

                //hydratation de la variable state avec un select qui renvoi tous les statecode IS CREA
                $state = $stateRepository->findOneBy(['stateCode'=>'CREA']);
                dump('CREA');
            }

            if($tripForm->get('publier')->isClicked()){

                $state = $stateRepository->findOneBy(['stateCode'=>'OPEN']);
                dump('OPEN');

            }

            //hydratation des attributs qui ne sont pas renseignés dans le formulaire
            $trip
                ->setOwner($this->getUser())
                //->setCampus($this->getUser()->getCampus())
                ->setState($state);

            $tripRepository->add($trip, true);
            $this->addFlash("success", "Sortie Ajoutée");
            return $this->redirectToRoute("filter");
        }

        return $this->render('create_trip/createTrip.twig', [
            'createTrip' => $tripForm->createView()
        ]);
    }

    #[Route('/update/trip/{id}', name: 'app_update_trip')]
    public function updateTrip($id, TripRepository $tripRepository, Request $request): Response
    {
        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);

        //création d'un formulaire (On utilise le même formulaire: CreateTripType)
        $tripForm = $this -> CreateForm(CreateTripType::class, $trip);
        $tripForm -> handleRequest($request);

        //suppression d'une sortie
        if ($tripForm->isSubmitted()){

            if($tripForm->get('supprimer')->isClicked()){

                $tripRepository->remove($trip, true);
                $this->addFlash("success", "Sortie Supprimée");

            }

            return $this->redirectToRoute("filter");
        }

        return $this->render('create_trip/updateTrip.twig', [
            'createTrip' => $tripForm->createView()
        ]);
    }

    #[Route('/display/trip/{id}', name: 'app_display_trip')]
    public function displayTrip($id, TripRepository $tripRepository, Request $request): Response
    {
        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);

        //récupération d'une liste de participants
        $listParticipants = $trip->getRegisteredParticipants();
        dump($listParticipants);

        return $this->render('create_trip/displayTrip.twig', ['trip' => $trip, 'listParticipants'=>$listParticipants]);
    }



}
