<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit comporter au moins 2 caractères',
        maxMessage: 'Le nom doit comporter au plus 50 caractères',
    )]
    private $name;

    #[ORM\Column(type: 'datetime')]
    //#[Asserts\Date]
    private $dateStartHour;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\Column(type: 'date')]
    private $dateLimitRegistration;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nbMaxRegistration;

    #[ORM\Column(type: 'text', nullable: true)]
    private $tripDescription;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'trip', fetch:'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'trip',fetch:'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private $place;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'trips', fetch:'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'trips', fetch: 'LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'participantTrips', fetch: 'EAGER')]
    private $registeredParticipants;

    public function __construct()
    {
        $this->registeredParticipants = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStartHour(): ?\DateTimeInterface
    {
        return $this->dateStartHour;
    }

    public function setDateStartHour(\DateTimeInterface $dateStartHour): self
    {
        $this->dateStartHour = $dateStartHour;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitRegistration(): ?\DateTimeInterface
    {
        return $this->dateLimitRegistration;
    }

    public function setDateLimitRegistration(\DateTimeInterface $dateLimitRegistration): self
    {
        $this->dateLimitRegistration = $dateLimitRegistration;

        return $this;
    }

    public function getNbMaxRegistration(): ?int
    {
        return $this->nbMaxRegistration;
    }

    public function setNbMaxRegistration(?int $nbMaxRegistration): self
    {
        $this->nbMaxRegistration = $nbMaxRegistration;

        return $this;
    }

    public function getTripDescription(): ?string
    {
        return $this->tripDescription;
    }

    public function setTripDescription(?string $tripDescription): self
    {
        $this->tripDescription = $tripDescription;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOwner(): ?Participant
    {
        return $this->owner;
    }

    public function setOwner(?Participant $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getRegisteredParticipants(): Collection
    {
        return $this->registeredParticipants;
    }

    public function addRegisteredParticipant(Participant $registeredParticipant): self
    {
        if (!$this->registeredParticipants->contains($registeredParticipant)) {
            $this->registeredParticipants[] = $registeredParticipant;
        }

        return $this;
    }

    public function removeRegisteredParticipant(Participant $registeredParticipant): self
    {
        $this->registeredParticipants->removeElement($registeredParticipant);

        return $this;
    }


}
