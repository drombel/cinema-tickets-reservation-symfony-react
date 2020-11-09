<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\ScreeningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ScreeningRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Screening
{
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cinema", inversedBy="screenings")
     * @JMS\MaxDepth(1)
     */
    private Cinema $cinema;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="screenings")
     * @JMS\MaxDepth(1)
     */
    private Movie $movie;

    /**
     * @ORM\Column(type="date")
     * @JMS\Type("DateTime<'d.m.Y'>")
     */
    private \DateTimeInterface $dateStart;

    /**
     * @ORM\Column(type="date")
     * @JMS\Type("DateTime<'d.m.Y'>")
     */
    private \DateTimeInterface $dateEnd;

    /**
     * @ORM\OneToMany(targetEntity="Showing", mappedBy="screening", cascade={"persist"})
     * @JMS\MaxDepth(1)
     */
    private Collection $showings;

    public function __construct()
    {
        $this->showings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        if ($dateEnd < $this->dateStart){
            throw new \InvalidArgumentException('Date end must be greater or equal of date start.');
        }
        $this->dateEnd = $dateEnd;

        return $this;
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

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

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

    public function __toString()
    {
        $start = $this->dateStart->format('d.m');
        $end = $this->dateEnd->format('d.m');
        return $this->getCinema().' '.$this->getMovie().' ('.$start.' - '.$end.')' ;
    }
}
