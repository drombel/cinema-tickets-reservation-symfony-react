<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\ShowingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ShowingRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Showing
{
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="showings")
     * @JMS\MaxDepth(1)
     */
    private Room $room;

    /**
     * @ORM\ManyToOne(targetEntity="Screening", inversedBy="showings")
     * @JMS\MaxDepth(1)
     */
    private ?Screening $screening;

    /**
     * @ORM\Column(type="time")
     * @JMS\Type("DateTime<'H:i'>")
     */
    private ?\DateTimeInterface $timeStart = null;

    /**
     * @ORM\Column(type="time")
     * @JMS\Type("DateTime<'H:i'>")
     */
    private ?\DateTimeInterface $timeEnd = null;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private string $price;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="showing", cascade={"persist"})
     * @JMS\MaxDepth(1)
     */
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->screening = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->timeStart;
    }

    public function setTimeStart(\DateTimeInterface $timeStart): self
    {
        $this->timeStart = $timeStart;
        if ($timeStart === $this->timeEnd)
            throw new \InvalidArgumentException("Times can't be the same.");

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;
        if ($timeEnd === $this->timeStart)
            throw new \InvalidArgumentException("Times can't be the same.");

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        if ($this->screening && !\in_array($room, $this->screening->getCinema()->getRooms()->toArray())){
            throw new \InvalidArgumentException("This room doesn't belongs to screening cinema");
        }
        $this->room = $room;

        return $this;
    }

    public function getScreening(): ?Screening
    {
        return $this->screening;
    }

    public function setScreening(?Screening $screening): self
    {
        $this->screening = $screening;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setShowing($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getShowing() === $this) {
                $ticket->setShowing(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $start = $this->timeStart->format('H:i');
        return $this->getScreening().'('.$start.')('.$this->room.')';
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function validate(LifecycleEventArgs $event)
    {
        $repo = $event->getObjectManager()->getRepository(Showing::class);
        $showing = $event->getObject();
        $result = $repo->checkForShowingsAtSameTime($showing);

        if ($result !== null) {
            throw new \InvalidArgumentException("Showing times overlays with another one. ");
        }

    }
}
