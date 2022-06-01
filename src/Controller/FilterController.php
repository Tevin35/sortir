<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    #[Route('/filter', name: 'filter')]
    public function index(TripRepository $tripRepository): Response
    {
        $trips = $tripRepository->findSearch();



        return $this->render('filter/index.html.twig', [
            'filter' => 'FilterController',
        ]);
    }
}
