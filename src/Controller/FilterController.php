<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\Model\SearchData;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    #[Route('/filter', name: 'filter')]
    public function index(TripRepository $tripRepository, Request $request): Response
    {
        /**
         * pour avoir l'autocomplétion de tous les attributs de Participant
         * @var Participant $currentUser
         */
        $currentUser = $this->getUser();
        $trip = new Trip();



        //Data initialization
        $SearchData = new SearchData();
        //Create form who use data
        $form = $this->createForm(FilterType::class, $SearchData);
        $form->handleRequest($request);
        $listTrips = $tripRepository->findSearch($SearchData);



        if($form -> isSubmitted()) {
            $listTrips = $tripRepository->findSearch($SearchData);
        }






        return $this->render('filter/index.html.twig', [
            'listTrips' => $listTrips,
            'filterForm' => $form->createView()
        ]);
    }
}
