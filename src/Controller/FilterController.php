<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\State;
use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\Model\SearchData;
use App\Repository\CampusRepository;
use App\Repository\StateRepository;
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


        //Data initialization
        $SearchData = new SearchData();
        $trip = new Trip();

        //Create form who use data
        $form = $this->createForm(FilterType::class, $SearchData);
        $form->handleRequest($request);
        $listTrips = $tripRepository->findSearch($SearchData );


        if ($form->isSubmitted() && $form->isValid()) {
            $listTrips = $tripRepository->findSearch($SearchData);

        }


        return $this->render('filter/index.html.twig', [
            'listTrips' => $listTrips,
            'filterForm' => $form->createView()
        ]);

    }


    #[Route('/register/{id}', name: 'register')]
    public function register($id, TripRepository $tripRepository, Request $request, EntityManagerInterface $em, StateRepository $stateRepository): Response
    {
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $currentUser
         */

        $currentUser = $this->getUser();
//        dd($currentUser);
        $trip = $tripRepository->find($id);
        $state = $stateRepository->findOneBy(['stateCode' => 'OPEN']);
        $trip->addRegisteredParticipant($currentUser);

        if (count($trip->getRegisteredParticipants()) === $trip->getNbMaxRegistration()) {
            $state = $stateRepository->findOneBy(['stateCode' => 'FENC']);
        }
        $trip->setState($state);

//        $em->persist($state);
        $em->persist($trip);
        $em->flush();

        return $this->redirectToRoute('filter');
    }


    #[Route('/unsubscribe/{id}', name: 'unsubscribe')]
    public function unsubscribe($id, TripRepository $tripRepository, Request $request, EntityManagerInterface $em, StateRepository $stateRepository): Response
    {
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $currentUser
         */

        $currentUser = $this->getUser();
//        dd($currentUser);
        $trip = $tripRepository->find($id);
        $state = $stateRepository->findOneBy(['stateCode' => 'OPEN']);
        $trip->removeRegisteredParticipant($currentUser);

        if (count($trip->getRegisteredParticipants()) < $trip->getNbMaxRegistration()) {
            $state = $stateRepository->findOneBy(['stateCode' => 'OPEN']);
        }
        $trip->setState($state);


        $em->persist($trip);
//        $em->persist($state);
        $em->flush();

        return $this->redirectToRoute('filter');


    }

}
