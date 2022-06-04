<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\State;
use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\Model\SearchData;
use App\Repository\TripRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FilterController extends AbstractController
{


    #[Route('/filter', name: 'filter')]
    public function index(TripRepository $tripRepository, Request $request): Response
    {

        //Pas besoin de getUser ici car on le place dans le constructeur du repository comme dans les fixtures au final
        //On sera en revanche obligé de déclarer une variable avant.

        $trip = new Trip();

        //Data initialization
        $SearchData = new SearchData();
        $state = new State();

        //Create form who use data
        $form = $this->createForm(FilterType::class, $SearchData);
        $form->handleRequest($request);
        $listTrips = $tripRepository->findSearch($SearchData, $state);

        if ($form->isSubmitted()) {
            $listTrips = $tripRepository->findSearch($SearchData, $state);
            dump($listTrips);
        }


        return $this->render('filter/index.html.twig', [
            'listTrips' => $listTrips,
            'filterForm' => $form->createView()
        ]);
    }


    #[Route('/register/{id}', name: 'register')]
    public function register($id, TripRepository $tripRepository, Request $request, EntityManagerInterface $em): Response
    {
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $currentUser
         */

        $currentUser = $this->getUser();
//        dd($currentUser);
        $trip = $tripRepository->find($id);
        $trip->addRegisteredParticipant($currentUser);
        $em->persist($trip);
        $em->flush();

        return $this->redirectToRoute('filter');


    }


    #[Route('/unsubscribe', name: 'unsubscribe')]
    public function unsubscribe(TripRepository $tripRepository, Request $request): Response
    {
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $currentUser
         */
        $trip = new Trip();
        $currentUser = $this->getUser();
        $trip->removeRegisteredParticipant($currentUser);
        return $this->redirectToRoute('filter');

    }

}