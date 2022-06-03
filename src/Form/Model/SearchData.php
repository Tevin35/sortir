<?php

namespace App\Form\Model;

use App\Entity\Campus;
use App\Entity\Trip;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\FormTypeInterface;


class SearchData
{
    private $campus;
    private $search;
    private $startingDate;
    private $endingDate;
    private $ownerTrip;
    private $registerTrip;
    private $unsuscribeTrip;
    private $pastTrip;


    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     * @return SearchData
     */
    public function setCampus($campus)
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     * @return SearchData
     */
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * @param mixed $startingDate
     * @return SearchData
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * @param mixed $endingDate
     * @return SearchData
     */
    public function setEndingDate($endingDate)
    {
        $this->endingDate = $endingDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOwnerTrip()
    {
        return $this->ownerTrip;
    }

    /**
     * @param mixed $ownerTrip
     * @return SearchData
     */
    public function setOwnerTrip($ownerTrip)
    {
        $this->ownerTrip = $ownerTrip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegisterTrip()
    {
        return $this->registerTrip;
    }

    /**
     * @param mixed $registerTrip
     * @return SearchData
     */
    public function setRegisterTrip($registerTrip)
    {
        $this->registerTrip = $registerTrip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnsuscribeTrip()
    {
        return $this->unsuscribeTrip;
    }

    /**
     * @param mixed $unsuscribeTrip
     * @return SearchData
     */
    public function setUnsuscribeTrip($unsuscribeTrip)
    {
        $this->unsuscribeTrip = $unsuscribeTrip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPastTrip()
    {
        return $this->pastTrip;
    }

    /**
     * @param mixed $pastTrip
     * @return SearchData
     */
    public function setPastTrip($pastTrip)
    {
        $this->pastTrip = $pastTrip;
        return $this;
    }


}