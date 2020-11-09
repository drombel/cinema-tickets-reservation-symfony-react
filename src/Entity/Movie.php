<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @Vich\Uploadable()
 * @Fresh\VichSerializableClass
 * @ORM\HasLifecycleCallbacks
 */
class Movie
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
    private string $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\ManyToMany(targetEntity="MovieCategory", inversedBy="movies")
     * @ORM\JoinTable(name="movies_has_categories")
     * @JMS\MaxDepth(1)
     */
    private Collection $categories;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @JMS\Type("DateTime<'d.m.Y'>")
     */
    private ?\DateTimeInterface $premiere_date;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @JMS\Type("integer")
     */
    private int $length;

    /**
     * @ORM\OneToMany(targetEntity="Screening", mappedBy="movie", cascade={"persist"})
     * @JMS\MaxDepth(1)
     */
    private Collection $screenings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Fresh\VichSerializableField("posterImage")
     */
    private ?string $poster;

    /**
     * @Assert\Image(
     *     maxSize = "15Mi",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     * )
     * @JMS\Exclude
     * @Vich\UploadableField(mapping="posters", fileNameProperty="poster")
     * @var File
     */
    private $posterImage;

    /**
     * @ORM\OneToMany(targetEntity="MovieScreenImage", mappedBy="movie", cascade={"persist", "remove"})
     * @JMS\MaxDepth(1)
     */
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->screenings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPremiereDate(): ?\DateTimeInterface
    {
        return $this->premiere_date;
    }

    public function setPremiereDate(?\DateTimeInterface $premiere_date): self
    {
        $this->premiere_date = $premiere_date;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function addImage(MovieScreenImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setMovie($this);
        }

        return $this;
    }

    public function removeImage(MovieScreenImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getMovie() === $this) {
                $image->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovieScreenImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getPoster(): ?string
    {
        return $this->poster ?? '';
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPosterImage()
    {
        return $this->posterImage;
    }

    public function setPosterImage($posterImage): self
    {
        $this->posterImage = $posterImage;
        if ($posterImage){
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return Collection|MovieCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(MovieCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(MovieCategory $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->title;
    }
}
