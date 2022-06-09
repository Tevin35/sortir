<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\CancelTripType;
use App\Form\CreateTripType;
use App\Form\FilterType;
use App\Form\Model\SearchData;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use App\Service\UpdateState;
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $trip = new Trip();

        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $user
         */
        $user = $this->getUser();

        //création d'un formulaire
        $tripForm = $this->CreateForm(CreateTripType::class, $trip);
        $tripForm->handleRequest($request);


        //traitement du formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            //On conditionne la valeur de State en fonction du bouton qui a été cliqué
            if ($tripForm->get('enregistrer')->isClicked()) {

                //hydratation de la variable state avec un select qui renvoi tous les statecode IS CREA
                $state = $stateRepository->findOneBy(['stateCode' => 'CREA']);
            }

            if ($tripForm->get('publier')->isClicked()) {

                $state = $stateRepository->findOneBy(['stateCode' => 'OPEN']);

            }

            //hydratation des attributs qui ne sont pas renseignés dans le formulaire
            $trip
                ->setOwner($this->getUser())
                //->setCampus($this->getUser()->getCampus())
                ->setState($state);

            //envoie en BDD
            $tripRepository->add($trip, true);
            $this->addFlash("success", "Sortie Ajoutée");
            return $this->redirectToRoute("filter");
        }

        return $this->render('create_trip/createTrip.twig', [
            'createTrip' => $tripForm->createView()
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////

    #[Route('/update/trip/{id}', name: 'app_update_trip')]
    public function updateTrip($id, TripRepository $tripRepository, StateRepository $stateRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);

        //création d'un formulaire (On utilise le même formulaire: CreateTripType)
        $tripForm = $this->CreateForm(CreateTripType::class, $trip);
        $tripForm->handleRequest($request);

        //suppression d'une sortie avant publication
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            //On conditionne la valeur de State en fonction du bouton qui a été cliqué
            if ($tripForm->get('enregistrer')->isClicked()) {

                //hydratation de la variable state avec un select qui renvoi tous les statecode IS CREA
                $state = $stateRepository->findOneBy(['stateCode' => 'CREA']);
                $trip->setState($state);
                $tripRepository->add($trip, true);
                $this->addFlash('success', 'Sortie enregistrée');

            }

            if ($tripForm->get('publier')->isClicked()) {

                $state = $stateRepository->findOneBy(['stateCode' => 'OPEN']);
                $trip->setState($state);
                $tripRepository->add($trip, true);
                $this->addFlash('success', 'Sortie publiée');


            }

            if ($tripForm->get('supprimer')->isClicked()) {

                $tripRepository->remove($trip, true);
                $this->addFlash('success', 'Sortie supprimée');

            }

            if ($tripForm->get('annuler')->isClicked()) {


                return $this->redirectToRoute('app_cancel_trip', ['id' => $trip->getId()]);

            }
            return $this->redirectToRoute("filter");
        }

        return $this->render('create_trip/updateTrip.twig', [
            'trip' => $trip,
            'createTrip' => $tripForm->createView()
        ]);

    }

    //////////////////////////////////////////// FLO - BOUTON PUBLIER PAGE D'ACCUEIL ///////////////////////////////////////////////////

    #[Route('/published/trip/{id}', name: 'app_published_trip')]
    public function published($id, TripRepository $tripRepository, Request $request, UpdateState $published): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $published->published($id);

        //Pas besoin de getUser ici car on le place dans le constructeur du repository comme dans les fixtures au final
        //On sera en revanche obligé de déclarer une variable avant.
        $this->addFlash('success', 'Sortie publiée');


        return $this->redirectToRoute("filter");

    }

    ///////////////////////////////////////////////////////////////////////////////////////////////


    #[Route('/cancel/trip/{id}', name: 'app_cancel_trip')]
    public function cancelTrip($id, TripRepository $tripRepository, StateRepository $stateRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);
        $tripDescription = $trip->getTripDescription();

        $cancelForm = CancelTripType::class;
        $form = $this->createForm($cancelForm);
        $form->handleRequest($request);

        //$cancelForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $motif = $form['cancelMotif']->getData();


            $state = $stateRepository->findOneBy(['stateCode' => 'CANC']);
            $trip
                ->setState($state)
                ->setTripDescription($tripDescription . ' Motif d\'annulation : ' . $motif);
            $tripRepository->add($trip, true);
            $this->addFlash('success', 'Sortie annulée');
            return $this->redirectToRoute('filter');
        }

        return $this->render('create_trip/cancelTrip.twig', [
            'cancelTrip' => $form->createView()
        ]);
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////


    #[Route('/display/trip/{id}', name: 'app_display_trip')]
    public function displayTrip($id, TripRepository $tripRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //récupération de l'id d'une sortie
        $trip = $tripRepository->find($id);

        //récupération d'une liste de participants
        $listParticipants = $trip->getRegisteredParticipants();

        return $this->render('create_trip/displayTrip.twig', ['trip' => $trip, 'listParticipants' => $listParticipants]);
    }

}
