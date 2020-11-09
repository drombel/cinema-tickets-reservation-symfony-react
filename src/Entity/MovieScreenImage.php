<?php

namespace App\Entity;

use App\Entity\Traits\TimestampTrait;
use App\Repository\MovieScreenImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;

/**
 * @ORM\Entity(repositoryClass=MovieScreenImageRepository::class)
 * @Vich\Uploadable()
 * @Fresh\VichSerializableClass
 * @ORM\HasLifecycleCallbacks
 */
class MovieScreenImage
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
     * @Fresh\VichSerializableField("imageFile")
     */
    private string $image;

    /**
     * @Assert\Image(
     *     maxSize = "15Mi",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     * )
     * @JMS\Exclude
     * @Vich\UploadableField(mapping="movieScreens", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="images")
     * @JMS\MaxDepth(0)
     */
    private Movie $movie;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param $imageFile
     * @return $this
     * @throws \Exception
     */
    public function setImageFile($imageFile): self
    {
        $this->imageFile = $imageFile;
        if ($imageFile){
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * @return Movie
     */
    public function getMovie(): Movie
    {
        return $this->movie;
    }


    /**
     * @param Movie $movie
     * @return $this
     */
    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
