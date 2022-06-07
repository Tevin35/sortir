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
            $dateOfFenced = $entity->getDateLimitRegistration();
            //Date, heure et minutes de la sortie
            $dateOfTrip = $entity->getDateStartHour()->format('Y-m-d H:i');
            $durationOfTrip = $entity->getDuration();
//            $endOfTrip = strtotime($dateOfTrip.'+'.$durationOfTrip.'minute');

            if ($dateNow > $dateOfFenced) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'FENC']);
                $entity->setState($state);
                $this->em->flush();
            }

            if ($dateOfTrip >= $dateNow) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'PROG']);
                $entity->setState($state);
                $this->em->flush();
            }

//            if ($endOfTrip >= $dateNow){
//                $state = $this->stateRepository->findOneBy(['stateCode' => 'CLOS']);
//                $entity->setState($state);
//                $this->em->flush();
//            }
//
//
//            if ($dateOfTrip->modify('+ 1 month') >= $dateNow) {
//                $state = $this->stateRepository->findOneBy(['stateCode' => 'HIST']);
//                $entity->setState($state);
//                $this->em->flush();
//            }
        }
    }


}