<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Ticket
{
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Showing", inversedBy="tickets")
     * @JMS\MaxDepth(1)
     */
    private Showing $showing;

    /**
     * @ORM\ManyToOne(targetEntity="Seat", inversedBy="tickets")
     * @JMS\MaxDepth(1)
     */
    private Seat $seat;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @JMS\Type("DateTime<'d.m.Y'>")
     */
    private ?\DateTimeInterface $date;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $surname;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getShowing(): ?Showing
    {
        return $this->showing;
    }

    public function setShowing(?Showing $showing): self
    {
        $this->showing = $showing;

        return $this;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): self
    {
        if (!\in_array($seat, $this->showing->getRoom()->getSeats()->toArray())){
            throw new \InvalidArgumentException("This seat doesn't belongs to showing room.");
        }
        if ($seat->getStatus() !== Seat::STATUS_ACTIVE){
            throw new \InvalidArgumentException("This seat isn't available to use.");
        }

        $this->seat = $seat;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function validate(LifecycleEventArgs $event)
    {
        /** @var TicketRepository $repo */
        $repo = $event->getObjectManager()->getRepository(Ticket::class);
        /** @var Ticket $ticket */
        $ticket = $event->getObject();

        $result = $repo->findByTaken($ticket->getShowing(), $ticket->getSeat(), $ticket->getDate());

        if ($result !== null)
            throw new \InvalidArgumentException('This seat is taken for selected day and showing.');
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $screening = $this->getShowing()->getScreening();

        if (!( $screening->getDateStart() <= $date && $date <= $screening->getDateEnd() ))
            throw new \InvalidArgumentException('Date selected for ticket must be between screening dates.');

        $this->date = $date;
        return $this;
    }

    public function __toString()
    {
        return "#".$this->id." ".$this->name." ".$this->surname;
    }

}
