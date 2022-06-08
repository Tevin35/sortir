<?php

namespace App\Service;


use App\Entity\Trip;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UpdateState
{

    private EntityManagerInterface $em;
    private StateRepository $stateRepository;
    private TripRepository $tripRepository;


    public function __construct(EntityManagerInterface $em, StateRepository $stateRepository, TripRepository $tripRepository)
    {
        $this->em = $em;
        $this->stateRepository = $stateRepository;
        $this->tripRepository = $tripRepository;
    }


    public function update()
    {
        $trips = $this->tripRepository->findAll();

        dump($trips);

        foreach ($trips as $entity) {

            //Date time du jour
            $dateNow = new \DateTime();
            //Date de cloture
            $dateOfFenced = clone $entity->getDateLimitRegistration();
            //Date, heure et minutes de la sortie
            $dateOfTrip = clone $entity->getDateStartHour();



            $endOfTrip = clone $entity->getDateLimitRegistration();
            $endOfTrip->modify($entity->getDuration(). ' minutes');

            $archiveOfTrip = clone $dateOfTrip->modify('1 month');


            if (($dateNow >= $archiveOfTrip) && ($entity->getState()->getStateCode() == 'CLOS' OR $entity->getState()->getStateCode() == 'CANC')){
                $state = $this->stateRepository->findOneBy(['stateCode' => 'HIST']);
                $entity->setState($state);
                $this->em->flush();
            }

            elseif(($dateNow >= $endOfTrip) && ($entity->getState()->getStateCode() == 'PROG')){
                $state = $this->stateRepository->findOneBy(['stateCode' => 'CLOS']);
                $entity->setState($state);
                $this->em->flush();
            }

            elseif ($dateNow >= $dateOfTrip && $entity->getState()->getStateCode() == 'FENC') {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'PROG']);
                $entity->setState($state);
                $this->em->flush();
            }

            elseif (($dateNow >= $dateOfFenced ) && ($dateNow < $dateOfTrip)  && ($entity->getState()->getStateCode() === 'OPEN')) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'FENC']);
                $entity->setState($state);
                $this->em->flush();
            }

            elseif(($dateNow < $dateOfFenced)  && ($entity->getState()->getStateCode() != 'OPEN' &&  $entity->getState()->getStateCode() != 'CREA')) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'OPEN']);
                $entity->setState($state);
                $this->em->flush();
            }


        }
    }


}