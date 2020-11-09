<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\MovieCategoryRepository;
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
 * @ORM\Entity(repositoryClass=MovieCategoryRepository::class)
 * @Vich\Uploadable()
 * @Fresh\VichSerializableClass
 * @ORM\HasLifecycleCallbacks
 */
class MovieCategory
{
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", mappedBy="categories")
     * @JMS\MaxDepth(1)
     */
    private Collection $movies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Fresh\VichSerializableField("imageFile")
     */
    private ?string $image;

    /**
     * @Assert\Image(
     *     maxSize = "15Mi",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     * )
     * @JMS\Exclude
     * @Vich\UploadableField(mapping="movieCategory", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
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

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addCategory($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
            $movie->removeCategory($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

}
