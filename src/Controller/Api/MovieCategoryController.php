<?php
namespace App\Controller\Api;

use App\Entity\MovieCategory;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/moviecategories")
 */
class MovieCategoryController extends ApiController
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        parent::__construct($em, $serializer, MovieCategory::class);
    }

    /**
     * @Route("/", methods="GET", name="api.moviecategory.find.all")
     * @param Request $request
     * @return JsonResponse
     */
	public function index(Request $request)
	{
        $data = $this->serialize($this->getData($request));
        $response = $this->respond($data)
            ->setEtag("Movie Categories")
            ->setPublic()
            ->setSharedMaxAge(1800)
        ;
        $response->isNotModified($request);
        return $response;
    }

    /**
     * @Route("/{id<\d+>}", methods="GET", name="api.moviecategory.find.id")
     * @Route("/{slug<\S+>}", methods="GET", name="api.moviecategory.find.slug")
     * @param Request $request
     * @param MovieCategory $object
     * @return JsonResponse
     */
    public function find(Request $request, MovieCategory $object = null)
    {
        if(!$object)
            return $this->respondNotFound('Category not found.');

        $data = $this->serialize($object);
        $response =  $this->respond($data)
            ->setEtag("MovieCategory ~".$object->getId()." ~ ".$object->getUpdatedAt()->format("Y-m-d_H:i:s")." ~ ".$object->getMovies()->count())
            ->setLastModified($object->getUpdatedAt())
            ->setSharedMaxAge(1800)
            ->setPublic()
        ;
        $response->isNotModified($request);
        return $response;

    }

}