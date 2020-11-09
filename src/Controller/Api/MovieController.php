<?php
namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;

/**
 * @Route("/api/movies")
 */
class MovieController extends ApiController
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        parent::__construct($em, $serializer, Movie::class);
    }

    /**
     * @Route("/", methods="GET", name="api.movie.find.all")
     * @param Request $request
     * @return JsonResponse
     */
	public function index(Request $request)
	{
        $data = $this->serialize($this->getData($request));
        $response = $this->respond($data)
            ->setEtag("Movies")
            ->setPublic()
            ->setSharedMaxAge(1800)
        ;
        $response->isNotModified($request);
        return $response;
    }

    /**
     * @Route("/{id<\d+>}", methods="GET", name="api.movie.find.id")
     * @Route("/{slug<\S+>}", methods="GET", name="api.movie.find.slug")
     * @param Request $request
     * @param Movie $object
     * @return JsonResponse
     */
    public function findById(Request $request, Movie $object = null)
    {
        if(!$object)
            return $this->respondNotFound('Movie not found.');

        $data = $this->serialize($object);
        $response =  $this->respond($data)
            ->setEtag("Movie ~".$object->getId()." ~ ".$object->getUpdatedAt()->format("Y-m-d_H:i:s"))
            ->setLastModified($object->getUpdatedAt())
            ->setSharedMaxAge(1800)
            ->setPublic()
        ;
        $response->isNotModified($request);
        return $response;
    }

}