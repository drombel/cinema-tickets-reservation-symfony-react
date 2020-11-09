<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\CinemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CinemaRepository::class)
 * @Vich\Uploadable()
 * @Fresh\VichSerializableClass
 * @ORM\HasLifecycleCallbacks
 */
class Cinema
{
    use TimestampTrait;
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Fresh\VichSerializableField("imageFile")
     */
    private ?string $image;

    /**
     * @Vich\UploadableField(mapping="cinemas", fileNameProperty="image")
     * @JMS\Exclude
     * @Assert\Image(
     *     maxSize = "15Mi",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     * )
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="cinema", cascade={"persist", "remove"})
     * @JMS\MaxDepth(1)
     */
    private Collection $rooms;

    /**
     * @ORM\Column(type="text")
     */
    private string $address;

    /**
     * @ORM\Column(type="decimal")
     */
    private string $lat;

    /**
     * @ORM\Column(type="decimal")
     */
    private string $long;

    /**
     * @ORM\OneToMany(targetEntity="Screening", mappedBy="cinema", cascade={"persist"})
     * @JMS\MaxDepth(1)
     */
    private Collection $screenings;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->screenings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image ?? '';
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;
        if ($imageFile){
            $this->updatedAt = new \DateTime();
        }
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLong(): ?string
    {
        return $this->long;
    }

    public function setLong(string $long): self
    {
        $this->long = $long;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setCinema($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            // set the owning side to null (unless already changed)
            if ($room->getCinema() === $this) {
                $room->setCinema(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Screening[]
     */
    public function getScreenings(): Collection
    {
        return $this->screenings;
    }

    public function addScreening(Screening $screening): self
    {
        if (!$this->screenings->contains($screening)) {
            $this->screenings[] = $screening;
            $screening->setCinema($this);
        }

        return $this;
    }

    public function removeScreening(Screening $screening): self
    {
        if ($this->screenings->contains($screening)) {
            $this->screenings->removeElement($screening);
            // set the owning side to null (unless already changed)
            if ($screening->getCinema() === $this) {
                $screening->setCinema(null);
            }
        }

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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
