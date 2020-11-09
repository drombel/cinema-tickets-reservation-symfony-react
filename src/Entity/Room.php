<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="Cinema", inversedBy="rooms")
     * @JMS\MaxDepth(1)
     */
    private ?Cinema $cinema;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $rows;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $cols;

    /**
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="room", cascade={"persist", "remove"})
     * @JMS\MaxDepth(1)
     */
    private Collection $seats;

    /**
     * @ORM\OneToMany(targetEntity="Showing", mappedBy="room", cascade={"persist"})
     * @JMS\MaxDepth(1)
     */
    private Collection $showings;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
        $this->showings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCinema(): ?Cinema
    {
        return $this->cinema;
    }

    public function setCinema(?Cinema $cinema): self
    {
        $this->cinema = $cinema;

        return $this;
    }

    /**
     * @return Collection|Seat[]
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setRoom($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->contains($seat)) {
            $this->seats->removeElement($seat);
            // set the owning side to null (unless already changed)
            if ($seat->getRoom() === $this) {
                $seat->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Showing[]
     */
    public function getShowings(): Collection
    {
        return $this->showings;
    }

    public function addShowing(Showing $showing): self
    {
        if (!$this->showings->contains($showing)) {
            $this->showings[] = $showing;
            $showing->setScreening($this);
        }

        return $this;
    }

    public function removeShowing(Showing $showing): self
    {
        if ($this->showings->contains($showing)) {
            $this->showings->removeElement($showing);
            // set the owning side to null (unless already changed)
            if ($showing->getScreening() === $this) {
                $showing->setScreening(null);
            }
        }

        return $this;
    }

    public function getRows(): ?int
    {
        return $this->rows;
    }

    public function setRows(?int $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function getCols(): ?int
    {
        return $this->cols;
    }

    public function setCols(?int $cols): self
    {
        $this->cols = $cols;

        return $this;
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

    public function __toString()
    {
        return $this->name;
    }
}
