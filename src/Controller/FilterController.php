<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Form\Model\SearchData;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    #[Route('/filter', name: 'filter')]
    public function index(TripRepository $tripRepository): Response
    {
        //Data initialization
        $SearchData = new SearchData();
        //Create form who use data
        $form = $this->createForm(FilterType::class, $SearchData);
        $trips = $tripRepository->findSearch();

        return $this->render('filter/index.html.twig', [
            'trips' => '$trips',
            'filterForm' => $form->createView()
        ]);
    }
}
