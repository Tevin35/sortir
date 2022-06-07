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
//       $state = $this->stateRepository->

        $entity = $args->getObject();
        $state = $this->stateRepository->findOneBy(['stateCode']);

        if ($entity instanceof Trip) {
            //Date time du jour
            $dateNow = new \DateTime();
            //Date de cloture
            $dateOfFenced = $entity->getDateLimitRegistration();
            //Date, heure et minutes de la sortie
            $dateOfTrip = $entity->getDateStartHour();


            if ($dateOfFenced >= $dateNow) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'FENC']);
                $entity->setState($state);
                $this->em->flush();

            } elseif ($dateOfTrip >= $dateNow) {
                $state = $this->stateRepository->findOneBy(['stateCode' => 'PROG']);
                $entity->setState($state);
                $this->em->flush();
            }
//
//            if ($dateNow-> $endOfTrip){
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
//        }
        }

    }
}