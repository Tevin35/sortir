<?php

namespace App\Service;



use App\Repository\StateRepository;
use App\Repository\TripRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


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

    public function update():void
    {
        $trips = $this->tripRepository->findAll();

        foreach ($trips as $entity) {

            //Datetime du jour
            $dateNow = new DateTime();
            //Date de cloture
            $dateOfFenced = clone $entity->getDateLimitRegistration();
            //Date de la sortie
            $dateOfTrip = clone $entity->getDateStartHour();
            //Date de fin de la sortie modifier via un clone du début de la sortie
            $endOfTrip = clone $dateOfTrip;
            $endOfTrip->modify($entity->getDuration() . ' minutes');
            //Date d'archivage/historisation de la sortie
            $archiveOfTrip = clone $dateOfTrip;
            $archiveOfTrip->modify('1 month');

            if (($dateNow >= $archiveOfTrip) && ($entity->getState()->getStateCode() == 'CLOS' OR $entity->getState()->getStateCode() == 'CANC')){
                $state = $this->stateRepository->findOneBy(['stateCode' => 'HIST']);
                $entity->setState($state);
                $this->em->flush();
            }

            elseif($entity->getState()->getStateCode() == 'CANC'){
//              $state = $this->stateRepository->findOneBy(['stateCode' => 'CANC']);
                $state = $entity->getState();
                $entity->setState($state);
                $this->em->flush();

            }

            elseif(($dateNow >= $endOfTrip) && ($entity->getState()->getStateCode() == 'PROG')){
                $state = $this->stateRepository->findOneBy(['stateCode' => 'CLOS']);
                $entity->setState($state);
                $this->em->flush();

            }

            elseif (($dateNow >= $dateOfTrip) && ($entity->getState()->getStateCode() == 'FENC')) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'PROG']);
                $entity->setState($state);
                $this->em->flush();

            } elseif (($dateNow >= $dateOfFenced) && ($dateNow < $dateOfTrip) && ($entity->getState()->getStateCode() == 'OPEN')) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'FENC']);
                $entity->setState($state);
                $this->em->flush();

            } elseif (($dateNow < $dateOfFenced) && ($entity->getState()->getStateCode() != 'OPEN' && $entity->getState()->getStateCode() != 'CREA')) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'OPEN']);
                $entity->setState($state);
                $this->em->flush();
            }


        }


    }

    public function published($id):void
    {
        $trip = $this->tripRepository->find($id);


        $state = $this->stateRepository->findOneBy(['stateCode' => 'OPEN']);
        $trip->setState($state);
        $this->tripRepository->add($trip, true);

    }


}
