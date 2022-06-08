<?php

namespace App\EventListener;

use App\Entity\Trip;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\PostLoad;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints\Date;

class TripListener
{
    private EntityManagerInterface $em;
    private StateRepository $stateRepository;

    public function __construct(EntityManagerInterface $em, StateRepository $stateRepository)
    {
        $this->em = $em;
        $this->stateRepository = $stateRepository;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Trip) {
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