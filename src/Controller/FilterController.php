<?php

namespace App\Controller;

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
        //Data initialization
        $SearchData = new SearchData();
        //Create form who use data
        $form = $this->createForm(FilterType::class, $SearchData);
        $form->handleRequest($request);
        $listTrips = $tripRepository->findSearch($SearchData);
        dump($listTrips);

        return $this->render('filter/index.html.twig', [
            'listTrips' => $listTrips,
            'filterForm' => $form->createView()
        ]);
    }
}
