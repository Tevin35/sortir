<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\CreateTripType;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
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
        if($tripForm -> isSubmitted()){
            dump('la');

            //On conditionne la valeur de State en fonction du bouton qui a été cliqué
            if($request->get('enregistrer')){

                //hydratation de la variable state avec un select qui renvoi tous les statecode IS CREA
                $state = $stateRepository->findOneBy(['stateCode'=>'CREA']);
                dump('CREA');
            }

            if($request->get('publier')){

                $state = $stateRepository->findOneBy(['stateCode'=>'OPEN']);
                dump('OPEN');

            }

            $rec=$request->get('enregistrer');
            dump($rec);

            //hydratation des attributs qui ne sont pas renseignés dans le formulaire
            $trip
                ->setOwner($this->getUser())
                ->setCampus($this->getUser()->getCampus())
                ->setState($state);

            $tripRepository->add($trip, true);
            $this->addFlash("success", "Sortie Ajoutée");
           // return $this->redirectToRoute("filter");
        }

        return $this->render('create_trip/createTrip.twig', [
            'createTrip' => $tripForm->createView()
        ]);
    }
}
